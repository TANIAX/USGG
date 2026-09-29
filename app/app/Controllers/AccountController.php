<?php

namespace App\Controllers;

use App\Helpers\MailHelper;
use App\Helpers\SessionHelper;
use App\Helpers\ProfilePictureHelper;
use App\Repositories\UserRepository;

/**
 * "Mon compte": every connected person updates their profile, photo and password.
 */
class AccountController extends BaseController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = service('repository', 'User');
        helper('form');
    }

    public function index()
    {
        $user = $this->currentUser();
        return view('pages/account/index', [
            'user' => $user,
            'pictureUrl' => ProfilePictureHelper::url($user->picture),
            'roles' => array_values(array_intersect_key(\App\Helpers\RoleHelper::ROLES, array_flip(SessionHelper::getUserConnected()->getRolesAsStrings()))),
            'minLength' => PasswordResetController::PASSWORD_MIN_LENGTH,
            'maxLength' => PasswordResetController::PASSWORD_MAX_LENGTH,
        ]);
    }

    public function updateProfile()
    {
        $user = $this->currentUser();
        $post = array_map(fn($value) => is_string($value) ? trim($value) : $value, $this->request->getPost());
        $rules = [
            'firstname' => 'required|max_length[100]',
            'name' => 'required|max_length[100]',
            'totem' => 'permit_empty|max_length[100]',
            'phone' => 'permit_empty|max_length[30]',
        ];
        $messages = [
            'firstname' => ['required' => 'Le prénom est obligatoire.', 'max_length' => 'Le prénom est trop long.'],
            'name' => ['required' => 'Le nom est obligatoire.', 'max_length' => 'Le nom est trop long.'],
            'totem' => ['max_length' => 'Le totem est trop long.'],
            'phone' => ['max_length' => 'Le numéro de téléphone est trop long.'],
        ];
        if (!$this->validateData($post, $rules, $messages))
            return $this->redirectWithErrors('/mon-compte', array_values($this->validator->getErrors()))->withInput();

        $picture = ProfilePictureHelper::store();
        if (isset($picture['error']))
            return $this->redirectWithErrors('/mon-compte', $picture['error'])->withInput();

        $data = ['firstname' => $post['firstname'], 'name' => $post['name'], 'totem' => $post['totem'] ?: null, 'phone' => $post['phone'] ?? ''];
        if ($picture['replace'] || ($post['remove_picture'] ?? '') === '1') {
            $data['picture'] = $picture['picture'] ?? '';
            ProfilePictureHelper::delete($user->picture);
        }
        $this->userRepository->updateProfile((int) $user->id, $data);
        // The name shown in the header comes from the session
        SessionHelper::refreshConnectedUser();

        $this->session->setFlashdata('success', 'Votre profil a été enregistré.');
        return redirect()->to(base_url('/mon-compte'));
    }

    public function updatePassword()
    {
        $user = $this->currentUser();
        $current = (string) $this->request->getPost('current_password');
        $password = (string) $this->request->getPost('password');
        $confirmation = (string) $this->request->getPost('password_confirmation');

        $errors = [];
        if (!password_verify($current, (string) $user->password))
            $errors[] = 'Le mot de passe actuel est incorrect.';
        if (strlen($password) < PasswordResetController::PASSWORD_MIN_LENGTH || strlen($password) > PasswordResetController::PASSWORD_MAX_LENGTH)
            $errors[] = 'Le nouveau mot de passe doit contenir entre ' . PasswordResetController::PASSWORD_MIN_LENGTH . ' et ' . PasswordResetController::PASSWORD_MAX_LENGTH . ' caractères.';
        if ($password !== $confirmation)
            $errors[] = 'Les deux nouveaux mots de passe ne sont pas identiques.';
        if ($errors) {
            if (!password_verify($current, (string) $user->password))
                log_message('notice', 'Changement de mot de passe refusé : mot de passe actuel incorrect');
            return $this->redirectWithErrors('/mon-compte#mot-de-passe', $errors);
        }

        $this->userRepository->update((int) $user->id, ['password' => password_hash($password, PASSWORD_DEFAULT), 'updated_at' => date('Y-m-d H:i:s')]);
        // Links to choose a password sent before do not work anymore
        service('repository', 'PasswordReset')->invalidateForUser((int) $user->id);
        MailHelper::send($user->email, 'Votre mot de passe a été modifié', 'emails/password_changed', [
            'totem' => $user->totem ?: $user->firstname,
            'forgotLink' => base_url('auth/mot-de-passe-oublie'),
        ]);

        $this->session->setFlashdata('success', 'Votre mot de passe a été modifié.');
        return redirect()->to(base_url('/mon-compte#mot-de-passe'));
    }

    /**
     * Row of the connected account (password hash included, never sent to the view)
     */
    private function currentUser()
    {
        return \Config\Database::connect()->table('user')->where('id', SessionHelper::getUserConnected()->getId())->get()->getRowObject();
    }
}
