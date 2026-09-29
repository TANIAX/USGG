<?php

namespace App\Controllers;

use App\Helpers\RoleHelper;
use App\Helpers\AccountHelper;
use App\Helpers\SessionHelper;
use App\Controllers\BaseController;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;

/**
 * Management of the accounts and of their roles (super admin).
 * Safeguards: a super admin can not remove their own super admin role nor deactivate their own account,
 * and there must always be at least one active super admin.
 */
class UserController extends BaseController
{
    private UserRepository $userRepository;
    private RoleRepository $roleRepository;

    public function __construct()
    {
        $this->userRepository = service('repository', 'User');
        $this->roleRepository = service('repository', 'Role');
        helper('form');
    }

    public function index()
    {
        return view('pages/admin/user/index', [
            'users' => $this->toJson($this->userRepository->getAllForAdmin()),
            'roles' => RoleHelper::ROLES,
            'currentUserId' => $this->currentUserId(),
        ]);
    }

    public function create()
    {
        return $this->form();
    }

    public function edit($id)
    {
        $user = $this->getUser((int) $id);
        if ($user === null)
            return $this->notFound();

        return $this->form($user);
    }

    public function store()
    {
        $data = $this->validateUser(null);
        if ($data === null)
            return redirect()->to(base_url('/admin/utilisateurs/create'))->withInput();

        $existing = $this->userRepository->findAnyByEmail($data['email']);
        if ($existing !== null) {
            $this->session->setFlashdata('errors', ['Un compte existe déjà pour cette adresse : modifiez-le plutôt.']);
            return redirect()->to(base_url('/admin/utilisateurs/edit/' . $existing->id));
        }

        [$userId, $sent] = AccountHelper::createAndInvite($data['profile'] + ['email' => $data['email']]);
        $this->roleRepository->setUserRoles($userId, $data['roles']);

        $this->session->setFlashdata('success', $sent
            ? 'Le compte de ' . $data['email'] . ' a été créé : un e-mail lui a été envoyé pour choisir son mot de passe.'
            : 'Le compte de ' . $data['email'] . ' a été créé, mais l\'e-mail n\'a pas pu être envoyé : utilisez « Envoyer un lien » depuis sa fiche.');
        return redirect()->to(base_url('/admin/utilisateurs'));
    }

    public function update($id)
    {
        $user = $this->getUser((int) $id);
        if ($user === null)
            return $this->notFound();

        $data = $this->validateUser($user);
        if ($data === null)
            return redirect()->to(base_url('/admin/utilisateurs/edit/' . $user->id))->withInput();

        $this->userRepository->updateProfile($user->id, $data['profile']);
        $this->roleRepository->setUserRoles($user->id, $data['roles']);
        if ($data['active'] !== $user->exists)
            $this->userRepository->setActive($user->id, $data['active']);

        $this->session->setFlashdata('success', 'Le compte de ' . $user->email . ' a été modifié.' . ($data['active'] ? '' : ' Il est désactivé : la personne ne peut plus se connecter.'));
        return redirect()->to(base_url('/admin/utilisateurs'));
    }

    /**
     * Sends a link to choose a password (new account that did not receive / lost the e-mail, forgotten password...).
     */
    public function invite($id)
    {
        $user = $this->getUser((int) $id);
        if ($user === null)
            return $this->notFound();

        if (!$user->exists) {
            $this->session->setFlashdata('errors', ['Ce compte est désactivé : réactivez-le avant d\'envoyer un lien.']);
            return redirect()->to(base_url('/admin/utilisateurs/edit/' . $user->id));
        }

        $sent = AccountHelper::sendInvitation($user->id, $user->email, $user->totem ?: ($user->firstname ?: $user->email));
        $this->session->setFlashdata($sent ? 'success' : 'errors', $sent
            ? 'Un lien pour choisir un mot de passe a été envoyé à ' . $user->email . '.'
            : ['L\'e-mail n\'a pas pu être envoyé (vérifiez la configuration e-mail du serveur).']);
        return redirect()->to(base_url('/admin/utilisateurs/edit/' . $user->id));
    }

    private function form($user = null)
    {
        return view('pages/admin/user/form', [
            'user' => $user,
            'roles' => RoleHelper::ROLES,
            'userTypes' => $this->userRepository->getUserTypes(),
            'isCurrentUser' => $user !== null && $user->id === $this->currentUserId(),
        ]);
    }

    /**
     * @param object|null $user Account being edited, null for a new account
     * @return array|null ['email', 'profile' => array, 'roles' => array, 'active' => bool], null if invalid (errors in the flashdata)
     */
    private function validateUser($user)
    {
        $post = $this->request->getPost();
        $typeIds = array_column($this->userRepository->getUserTypes(), 'id');

        $rules = [
            'firstname' => 'required|max_length[100]',
            'name' => 'required|max_length[100]',
            'totem' => 'permit_empty|max_length[100]',
            'phone' => 'permit_empty|max_length[30]',
            'user_type_id' => 'required|in_list[' . implode(',', $typeIds) . ']',
        ];
        $messages = [
            'firstname' => ['required' => 'Le prénom est obligatoire.', 'max_length' => 'Le prénom est trop long.'],
            'name' => ['required' => 'Le nom est obligatoire.', 'max_length' => 'Le nom est trop long.'],
            'totem' => ['max_length' => 'Le totem est trop long.'],
            'phone' => ['max_length' => 'Le numéro de téléphone est trop long.'],
            'user_type_id' => ['required' => 'Choisissez la fonction.', 'in_list' => 'La fonction choisie n\'existe pas.'],
        ];
        if ($user === null) {
            $rules['email'] = 'required|valid_email|max_length[255]';
            $messages['email'] = ['required' => 'L\'adresse e-mail est obligatoire.', 'valid_email' => 'L\'adresse e-mail n\'est pas valide.', 'max_length' => 'L\'adresse e-mail est trop longue.'];
        }

        $errors = [];
        if (!$this->validateData($post, $rules, $messages))
            $errors = array_values($this->validator->getErrors());

        $roles = array_values(array_intersect(array_keys(RoleHelper::ROLES), (array) ($post['roles'] ?? [])));
        $active = $user === null ? true : ($post['active'] ?? '') === '1';

        // Safeguards on the super admins
        if ($user !== null) {
            $isCurrentUser = $user->id === $this->currentUserId();
            $wasSuperAdmin = in_array('super_admin', $user->roles, true);
            $staysSuperAdmin = in_array('super_admin', $roles, true) && $active;

            if ($isCurrentUser && !$active)
                $errors[] = 'Vous ne pouvez pas désactiver votre propre compte.';
            if ($isCurrentUser && $wasSuperAdmin && !in_array('super_admin', $roles, true))
                $errors[] = 'Vous ne pouvez pas retirer votre propre rôle de super administrateur.';
            if ($wasSuperAdmin && $user->exists && !$staysSuperAdmin && $this->userRepository->countActiveWithRole('super_admin', $user->id) === 0)
                $errors[] = 'Il doit toujours rester au moins un super administrateur actif.';
        }

        if ($errors) {
            $this->session->setFlashdata('errors', array_unique($errors));
            return null;
        }

        return [
            'email' => strtolower(trim($post['email'] ?? '')),
            'profile' => [
                'firstname' => trim($post['firstname']),
                'name' => trim($post['name']),
                'totem' => trim($post['totem'] ?? '') ?: null,
                'phone' => trim($post['phone'] ?? ''),
                'user_type_id' => (int) $post['user_type_id'],
            ],
            'roles' => $roles,
            'active' => $active,
        ];
    }

    private function getUser(int $id)
    {
        foreach ($this->userRepository->getAllForAdmin() as $user) {
            if ($user->id === $id)
                return $user;
        }
        return null;
    }

    private function currentUserId()
    {
        return (int) SessionHelper::getUserConnected()->getId();
    }

    private function notFound()
    {
        return $this->redirectWithErrors('/admin/utilisateurs', 'Ce compte n\'existe pas.');
    }
}
