<?php

namespace App\Controllers;

use Exception;
use App\Helpers\FileHelper;
use App\Helpers\AccountHelper;
use App\Helpers\ImageHelper;
use App\Controllers\BaseController;
use App\Repositories\UserRepository;
use App\Repositories\SectionRepository;
use App\Repositories\SectionLeaderRepository;

/**
 * Management of the section leaders (super admin), shown on the home page.
 * A leader is identified by the e-mail of their account: if the account does not exist it is created
 * and a link to choose its password is sent by e-mail.
 */
class LeaderController extends BaseController
{
    private const PICTURE_SIZE = 400;

    private UserRepository $userRepository;
    private SectionRepository $sectionRepository;
    private SectionLeaderRepository $leaderRepository;

    public function __construct()
    {
        $this->userRepository = service('repository', 'User');
        $this->sectionRepository = service('repository', 'Section');
        $this->leaderRepository = service('repository', 'SectionLeader');
        helper('form');
    }

    public function index()
    {
        return view('pages/admin/leader/index', [
            'leaders' => $this->toJson($this->leaderRepository->getLeaders()),
            'sections' => $this->toJson($this->sectionRepository->getAllOrdered()),
        ]);
    }

    public function create()
    {
        return $this->form();
    }

    public function edit($id)
    {
        $leader = $this->leaderRepository->getLeader((int) $id);
        if ($leader === null)
            return $this->notFound();

        return $this->form($leader);
    }

    /**
     * Existing account of an e-mail address (used to fill in the form).
     * GET admin/responsables/compte?email=...
     */
    public function account()
    {
        $user = $this->userRepository->findAnyByEmail((string) $this->request->getGet('email'));
        if ($user === null)
            return $this->response->setJSON(['exists' => false]);

        return $this->response->setJSON([
            'exists' => true,
            'active' => (bool) $user->exists,
            'totem' => $user->totem,
            'firstname' => $user->firstname,
            'name' => $user->name,
            'phone' => $user->phone,
            'user_type_id' => (int) $user->user_type_id,
            'picture_url' => SectionLeaderRepository::pictureUrl($user->picture),
        ]);
    }

    public function store()
    {
        $data = $this->validateLeader(true);
        if ($data === null)
            return redirect()->to(base_url('/admin/responsables/create'))->withInput();

        $user = $this->userRepository->findAnyByEmail($data['email']);
        if ($user !== null && $this->leaderRepository->isLeader((int) $user->id, $data['section_id'])) {
            $this->session->setFlashdata('errors', ['Cette personne est déjà responsable de cette section.']);
            return redirect()->to(base_url('/admin/responsables/create'))->withInput();
        }

        $picture = $this->processPicture();
        if (isset($picture['error'])) {
            $this->session->setFlashdata('errors', [$picture['error']]);
            return redirect()->to(base_url('/admin/responsables/create'))->withInput();
        }

        $messages = [];
        if ($user === null) {
            // New account: the person chooses their password with the link sent by e-mail
            [$userId, $sent] = AccountHelper::createAndInvite($data['profile'] + [
                'user_type_id' => $data['user_type_id'],
                'email' => $data['email'],
                'picture' => $picture['picture'] ?? null,
            ], 'en tant que responsable de section');
            $messages[] = $sent
                ? 'Un compte a été créé pour ' . $data['email'] . ' : un e-mail lui a été envoyé pour choisir son mot de passe.'
                : 'Un compte a été créé pour ' . $data['email'] . ', mais l\'e-mail n\'a pas pu être envoyé : la personne peut utiliser « Mot de passe oublié » sur la page de connexion.';
        } else {
            // Existing account: linked, its profile is updated with the form
            $userId = (int) $user->id;
            $profile = $data['profile'] + ($picture['replace'] ? ['picture' => $picture['picture']] : []);
            if (!$user->exists) {
                $profile['exists'] = true;
                $messages[] = 'Le compte de ' . $data['email'] . ' était désactivé, il a été réactivé.';
            }
            $this->userRepository->updateProfile($userId, $profile);
            if ($picture['replace'])
                $this->deletePicture($user->picture);
            $messages[] = 'Le compte existant de ' . $data['email'] . ' a été lié.';
        }

        $this->leaderRepository->add($userId, $data['section_id'], $data['user_type_id']);
        $this->session->setFlashdata('success', implode(' ', $messages));
        return redirect()->to(base_url('/admin/responsables'));
    }

    public function update($id)
    {
        $leader = $this->leaderRepository->getLeader((int) $id);
        if ($leader === null)
            return $this->notFound();

        $data = $this->validateLeader(false);
        if ($data === null)
            return redirect()->to(base_url('/admin/responsables/edit/' . $leader->id))->withInput();

        if ($data['section_id'] !== $leader->section_id && $this->leaderRepository->isLeader($leader->user_id, $data['section_id'], $leader->id)) {
            $this->session->setFlashdata('errors', ['Cette personne est déjà responsable de cette section.']);
            return redirect()->to(base_url('/admin/responsables/edit/' . $leader->id))->withInput();
        }

        $picture = $this->processPicture();
        if (isset($picture['error'])) {
            $this->session->setFlashdata('errors', [$picture['error']]);
            return redirect()->to(base_url('/admin/responsables/edit/' . $leader->id))->withInput();
        }

        $this->userRepository->updateProfile($leader->user_id, $data['profile'] + ($picture['replace'] ? ['picture' => $picture['picture']] : []));
        if ($picture['replace'])
            $this->deletePicture($leader->picture);
        $this->leaderRepository->updateLeader($leader->id, $data['section_id'], $data['user_type_id'], $data['section_id'] !== $leader->section_id);

        $this->session->setFlashdata('success', 'La fiche de ' . ($leader->display_name ?: $leader->email) . ' a été modifiée.');
        return redirect()->to(base_url('/admin/responsables'));
    }

    /**
     * Removes the person from the leaders of the section. The account is kept.
     */
    public function delete($id)
    {
        $leader = $this->leaderRepository->getLeader((int) $id);
        if ($leader === null)
            return $this->notFound();

        $this->leaderRepository->remove($leader->id);
        $this->session->setFlashdata('success', ($leader->display_name ?: $leader->email) . ' a été retiré(e) des responsables (section ' . $leader->section_name . ') ; son compte est conservé.');
        return redirect()->to(base_url('/admin/responsables'));
    }

    /**
     * Moves a leader up or down in their section (called in javascript).
     */
    public function move($id)
    {
        $moved = $this->leaderRepository->move((int) $id, $this->request->getPost('direction') === 'up' ? -1 : 1);
        return $this->response->setJSON(['success' => $moved, 'leaders' => $this->leaderRepository->getLeaders()]);
    }

    private function form($leader = null)
    {
        return view('pages/admin/leader/form', [
            'leader' => $leader,
            'sections' => $this->sectionRepository->getAllOrdered(),
            'userTypes' => $this->userRepository->getUserTypes(),
        ]);
    }

    /**
     * @return array|null ['email' => string, 'section_id' => int, 'profile' => array] or null if invalid (errors in the flashdata)
     */
    private function validateLeader(bool $withEmail)
    {
        $post = $this->request->getPost();
        $typeIds = array_column($this->userRepository->getUserTypes(), 'id');

        $rules = [
            'section_id' => ['label' => 'Section', 'rules' => 'required|in_list[' . implode(',', $this->sectionRepository->getAllIds()) . ']'],
            'firstname' => ['label' => 'Prénom', 'rules' => 'required|max_length[100]'],
            'name' => ['label' => 'Nom', 'rules' => 'required|max_length[100]'],
            'totem' => ['label' => 'Totem', 'rules' => 'permit_empty|max_length[100]'],
            'phone' => ['label' => 'Téléphone', 'rules' => 'permit_empty|max_length[30]'],
            'user_type_id' => ['label' => 'Fonction', 'rules' => 'required|in_list[' . implode(',', $typeIds) . ']'],
        ];
        $messages = [
            'section_id' => ['required' => 'Choisissez la section.', 'in_list' => 'La section choisie n\'existe pas.'],
            'firstname' => ['required' => 'Le prénom est obligatoire.', 'max_length' => 'Le prénom est trop long.'],
            'name' => ['required' => 'Le nom est obligatoire.', 'max_length' => 'Le nom est trop long.'],
            'totem' => ['max_length' => 'Le totem est trop long.'],
            'phone' => ['max_length' => 'Le numéro de téléphone est trop long.'],
            'user_type_id' => ['required' => 'Choisissez la fonction.', 'in_list' => 'La fonction choisie n\'existe pas.'],
        ];
        if ($withEmail) {
            $rules['email'] = ['label' => 'E-mail', 'rules' => 'required|valid_email|max_length[255]'];
            $messages['email'] = ['required' => 'L\'adresse e-mail est obligatoire.', 'valid_email' => 'L\'adresse e-mail n\'est pas valide.', 'max_length' => 'L\'adresse e-mail est trop longue.'];
        }

        if (!$this->validateData($post, $rules, $messages)) {
            $this->session->setFlashdata('errors', $this->validator->getErrors());
            return null;
        }

        return [
            'email' => strtolower(trim($post['email'] ?? '')),
            'section_id' => (int) $post['section_id'],
            // Function in the section
            'user_type_id' => (int) $post['user_type_id'],
            'profile' => [
                'firstname' => trim($post['firstname']),
                'name' => trim($post['name']),
                'totem' => trim($post['totem'] ?? '') ?: null,
                'phone' => trim($post['phone'] ?? ''),
            ],
        ];
    }

    /**
     * Stores the portrait sent with the form: square, 400px, re-encoded without metadata, in public/uploads/pp/.
     *
     * @return array ['replace' => false] | ['replace' => true, 'picture' => string] | ['error' => string]
     */
    private function processPicture()
    {
        $file = $this->request->getFile('picture');
        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE)
            return ['replace' => false];

        if (!$file->isValid())
            return ['error' => $file->getError() === UPLOAD_ERR_INI_SIZE ? 'La photo est trop lourde pour le serveur.' : 'La photo n\'a pas pu être envoyée.'];
        if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/webp', 'image/gif'], true))
            return ['error' => 'Format de photo non accepté (JPEG, PNG, WebP ou GIF).'];

        try {
            $name = ImageHelper::randomName() . '.jpg';
            ImageHelper::saveJpeg(ImageHelper::square(ImageHelper::open($file->getTempName()), self::PICTURE_SIZE), $this->picturePath($name));
        } catch (Exception $exception) {
            return ['error' => $exception->getMessage()];
        }

        return ['replace' => true, 'picture' => $name];
    }

    private function deletePicture(?string $picture)
    {
        // Only a file of the profile pictures directory, never a path given by someone
        if ($picture && basename($picture) === $picture && is_file($this->picturePath($picture)))
            unlink($this->picturePath($picture));
    }

    private function picturePath(string $name)
    {
        return ROOTPATH . 'public' . DIRECTORY_SEPARATOR . FileHelper::PROFIL_PICTURE_DIRECTORY . $name;
    }

    private function notFound()
    {
        $this->session->setFlashdata('errors', ['Ce responsable n\'existe pas ou a été retiré.']);
        return redirect()->to(base_url('/admin/responsables'));
    }

    private function toJson($data)
    {
        return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    }
}
