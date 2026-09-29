<?php

namespace App\Controllers;

use Exception;
use App\Helpers\LogHelper;
use App\Helpers\AuditHelper;
use App\Helpers\ImageHelper;
use App\Repositories\ContentRepository;

/**
 * Contents of the home page (super admin): questions of the FAQ and testimonials.
 */
class ContentController extends BaseController
{
    public const PICTURE_DIRECTORY = 'uploads/testimonials/';
    private const LABELS = ['faq' => 'Question', 'temoignage' => 'Témoignage'];

    private ContentRepository $contentRepository;

    public function __construct()
    {
        $this->contentRepository = service('repository', 'Content');
        helper('form');
    }

    public function index()
    {
        return view('pages/admin/content/index', [
            'questions' => $this->contentRepository->getItems('faq'),
            'testimonials' => $this->contentRepository->getItems('temoignage'),
        ]);
    }

    public function create($type)
    {
        return view('pages/admin/content/form', ['type' => $type, 'item' => null]);
    }

    public function edit($type, $id)
    {
        $item = $this->contentRepository->get($type, (int) $id);
        if ($item === null)
            return $this->notFound();

        return view('pages/admin/content/form', ['type' => $type, 'item' => $item]);
    }

    public function save($type, $id = null)
    {
        $item = $id !== null ? $this->contentRepository->get($type, (int) $id) : null;
        if ($id !== null && $item === null)
            return $this->notFound();

        $post = array_map(fn($value) => is_string($value) ? trim($value) : $value, $this->request->getPost());
        $back = '/admin/contenus/' . $type . ($item ? '/edit/' . $item->id : '/create');

        if ($type === 'faq') {
            $rules = ['question' => 'required|max_length[255]', 'answer' => 'required|max_length[5000]'];
            $messages = ['question' => ['required' => 'La question est obligatoire.', 'max_length' => 'La question est trop longue.'],
                'answer' => ['required' => 'La réponse est obligatoire.', 'max_length' => 'La réponse est trop longue.']];
        } else {
            $rules = ['quote' => 'required|max_length[1000]', 'name' => 'required|max_length[100]', 'totem' => 'permit_empty|max_length[100]'];
            $messages = ['quote' => ['required' => 'Le témoignage est obligatoire.', 'max_length' => 'Le témoignage est trop long (1000 caractères maximum).'],
                'name' => ['required' => 'Le nom est obligatoire.', 'max_length' => 'Le nom est trop long.'],
                'totem' => ['max_length' => 'Le totem est trop long.']];
        }
        if (!$this->validateData($post, $rules, $messages))
            return $this->redirectWithErrors($back, array_values($this->validator->getErrors()))->withInput();

        $data = ['is_active' => ($post['is_active'] ?? '') === '1'];
        if ($type === 'faq') {
            $data += ['question' => $post['question'], 'answer' => str_replace("\r\n", "\n", $post['answer'])];
        } else {
            $data += ['quote' => str_replace("\r\n", "\n", trim($post['quote'], " \"“”«»")), 'name' => $post['name'], 'totem' => ltrim($post['totem'] ?? '', '@') ?: null];
            $picture = $this->storePicture();
            if (isset($picture['error']))
                return $this->redirectWithErrors($back, $picture['error'])->withInput();
            if (isset($picture['name']) || ($post['remove_picture'] ?? '') === '1') {
                $data['picture'] = $picture['name'] ?? null;
                if ($item)
                    self::deletePicture($item->picture);
            }
        }

        $savedId = $this->contentRepository->save($type, $item->id ?? null, $data);
        $label = $type === 'faq' ? $data['question'] : $data['name'];
        AuditHelper::log($item ? 'updated' : 'created', self::LABELS[$type], $label, '/admin/contenus/' . $type . '/edit/' . $savedId);

        $this->session->setFlashdata('success', ($type === 'faq' ? 'La question' : 'Le témoignage de ' . $data['name']) . ' a été enregistré' . ($type === 'faq' ? 'e' : '') . '.'
            . ($data['is_active'] ? '' : ' Il n\'est pas affiché sur le site (inactif).'));
        return redirect()->to(base_url('/admin/contenus#' . $type));
    }

    /**
     * up, down, toggle (shown / hidden), delete
     */
    public function action($type, $id, $action)
    {
        $item = $this->contentRepository->get($type, (int) $id);
        if ($item === null)
            return $this->notFound();

        $label = $type === 'faq' ? $item->question : $item->name;
        if ($action === 'delete') {
            if ($type === 'temoignage')
                self::deletePicture($item->picture);
            $this->contentRepository->remove($type, $item->id);
            AuditHelper::log('deleted', self::LABELS[$type], $label);
            $this->session->setFlashdata('success', ($type === 'faq' ? 'La question a été supprimée.' : 'Le témoignage de ' . $item->name . ' a été supprimé.'));
        } elseif ($action === 'toggle') {
            $this->contentRepository->save($type, $item->id, ['is_active' => !$item->is_active]);
            AuditHelper::log('updated', self::LABELS[$type], $label . ($item->is_active ? ' (masqué' : ' (affiché') . ($type === 'faq' ? 'e)' : ')'));
        } else {
            $this->contentRepository->move($type, $item->id, $action);
        }

        return redirect()->to(base_url('/admin/contenus#' . $type));
    }

    /**
     * Url of the picture of a testimonial (an url, or a file of public/uploads/testimonials)
     */
    public static function pictureUrl(?string $picture): ?string
    {
        if (!$picture)
            return null;
        return preg_match('#^https?://#', $picture) ? $picture : base_url(self::PICTURE_DIRECTORY . $picture);
    }

    private function storePicture(): array
    {
        $file = $this->request->getFile('picture');
        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE)
            return [];
        if (!$file->isValid() || !in_array($file->getMimeType(), ['image/png', 'image/jpeg', 'image/webp', 'image/gif'], true))
            return ['error' => 'La photo n\'a pas pu être envoyée (JPEG, PNG, WebP ou GIF).'];

        try {
            $name = ImageHelper::randomName() . '.jpg';
            ImageHelper::saveJpeg(ImageHelper::square(ImageHelper::open($file->getTempName()), 256), ROOTPATH . 'public/' . self::PICTURE_DIRECTORY . $name);
        } catch (Exception $exception) {
            LogHelper::exception('Photo de témoignage non enregistrée', $exception);
            return ['error' => $exception->getMessage()];
        }
        return ['name' => $name];
    }

    private static function deletePicture(?string $picture): void
    {
        if ($picture && !preg_match('#^https?://#', $picture) && preg_match('/^[0-9a-f]+\.jpg$/', $picture))
            @unlink(ROOTPATH . 'public/' . self::PICTURE_DIRECTORY . $picture);
    }

    private function notFound()
    {
        return $this->redirectWithErrors('/admin/contenus', 'Ce contenu n\'existe pas.');
    }
}
