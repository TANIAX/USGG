<?php

namespace App\Controllers;

use Exception;
use App\Helpers\LogHelper;
use App\Helpers\AuditHelper;
use App\Helpers\ImageHelper;
use App\Repositories\SectionRepository;

/**
 * Sections (super admin): name, colour, logo, ages and presentation texts shown on the site
 * (menus, home page, pages Guides / Scouts, agenda, staff). The slug (anchor of the page) and the unit do not change.
 */
class SectionController extends BaseController
{
    private const LOGO_DIRECTORY = 'uploads/sections/';

    private SectionRepository $sectionRepository;

    public function __construct()
    {
        $this->sectionRepository = service('repository', 'Section');
        helper('form');
    }

    public function index()
    {
        return view('pages/admin/section/index', ['sections' => $this->sectionRepository->getAllForAdmin()]);
    }

    public function edit($id)
    {
        $section = $this->sectionRepository->get((int) $id);
        if ($section === null)
            return $this->notFound();

        return view('pages/admin/section/form', ['section' => $section]);
    }

    public function update($id)
    {
        $section = $this->sectionRepository->get((int) $id);
        if ($section === null)
            return $this->notFound();

        $post = array_map(fn($value) => is_string($value) ? trim($value) : $value, $this->request->getPost());
        $rules = [
            'name' => 'required|max_length[100]',
            'color' => 'required|regex_match[/^#[0-9a-fA-F]{6}$/]',
            'title' => 'permit_empty|max_length[150]',
            'group_name' => 'permit_empty|max_length[100]',
            'ages' => 'permit_empty|max_length[50]',
            'description' => 'permit_empty|max_length[10000]',
        ];
        $messages = [
            'name' => ['required' => 'Le nom est obligatoire.', 'max_length' => 'Le nom est trop long.'],
            'color' => ['required' => 'La couleur est obligatoire.', 'regex_match' => 'La couleur doit être au format #RRGGBB.'],
            'title' => ['max_length' => 'Le titre est trop long.'],
            'group_name' => ['max_length' => 'Le nom du groupe est trop long.'],
            'ages' => ['max_length' => 'Les âges sont trop longs.'],
            'description' => ['max_length' => 'Le texte est trop long.'],
        ];
        if (!$this->validateData($post, $rules, $messages))
            return $this->redirectWithErrors('/admin/sections/edit/' . $section->id, array_values($this->validator->getErrors()))->withInput();

        $data = [
            'name' => $post['name'],
            'color' => strtolower($post['color']),
            'title' => $post['title'] ?: null,
            'group_name' => $post['group_name'] ?: null,
            'ages' => $post['ages'] ?: null,
            'description' => str_replace("\r\n", "\n", $post['description'] ?? '') ?: null,
            // The section "Unité" (unit staff, events of the whole unit) can not be deactivated
            'exists' => $section->slug === 'unite' || ($post['exists'] ?? '') === '1',
        ];

        $logo = $this->storeLogo();
        if (isset($logo['error']))
            return $this->redirectWithErrors('/admin/sections/edit/' . $section->id, $logo['error'])->withInput();
        if (isset($logo['path'])) {
            $data['logo'] = $logo['path'];
            // The former uploaded logo is deleted (the logos of assets/img are kept)
            if (str_starts_with((string) $section->logo, self::LOGO_DIRECTORY) && is_file(ROOTPATH . 'public/' . $section->logo))
                @unlink(ROOTPATH . 'public/' . $section->logo);
        }

        $this->sectionRepository->updateSection($section->id, $data);
        AuditHelper::log('updated', 'Section', $data['name'], '/admin/sections/edit/' . $section->id);

        $this->session->setFlashdata('success', 'La section « ' . $data['name'] . ' » a été modifiée.' . ($data['exists'] ? '' : ' Elle est masquée sur le site.'));
        return redirect()->to(base_url('/admin/sections'));
    }

    public function move($id)
    {
        $direction = $this->request->getPost('direction') === 'up' ? 'up' : 'down';
        $this->sectionRepository->move((int) $id, $direction);
        return redirect()->to(base_url('/admin/sections'));
    }

    /**
     * New logo (optional): PNG with transparency, at most 512 px.
     * @return array [] without new logo, ['path' => ...] or ['error' => ...]
     */
    private function storeLogo(): array
    {
        $file = $this->request->getFile('logo');
        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE)
            return [];
        if (!$file->isValid())
            return ['error' => 'Le logo n\'a pas pu être envoyé.'];
        if (!in_array($file->getMimeType(), ['image/png', 'image/jpeg', 'image/webp', 'image/gif'], true))
            return ['error' => 'Format de logo non accepté (PNG, JPEG, WebP ou GIF).'];
        if ($file->getSize() > 5 * 1024 * 1024)
            return ['error' => 'Le logo dépasse 5 Mo.'];

        try {
            $path = self::LOGO_DIRECTORY . ImageHelper::randomName() . '.png';
            ImageHelper::saveTransparentPng(ImageHelper::open($file->getTempName()), ROOTPATH . 'public/' . $path);
        } catch (Exception $exception) {
            LogHelper::exception('Logo de section non enregistré', $exception);
            return ['error' => $exception->getMessage()];
        }

        return ['path' => $path];
    }

    private function notFound()
    {
        return $this->redirectWithErrors('/admin/sections', 'Cette section n\'existe pas.');
    }
}
