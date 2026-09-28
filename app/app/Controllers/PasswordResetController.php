<?php

namespace App\Controllers;

use App\Helpers\SessionHelper;
use App\Controllers\BaseController;
use App\Repositories\BaseRepository;
use App\Repositories\UserRepository;
use App\Repositories\PasswordResetRepository;

/**
 * "Forgotten password": a link containing a single-use token is sent by e-mail, it allows to choose a new password.
 * The answer is the same whether the address has an account or not, so that the page can not be used to find out who is registered.
 */
class PasswordResetController extends BaseController
{
    // Same rules as the login form (App\DTO\Request\Auth\LoginRequestDTO)
    private const PASSWORD_MIN_LENGTH = 8;
    private const PASSWORD_MAX_LENGTH = 32;

    // Limits: requests per IP address, and delay between two e-mails for the same account
    private const MAX_REQUESTS_PER_IP = 5;
    private const REQUESTS_PERIOD = 15 * MINUTE;
    private const DELAY_BETWEEN_EMAILS = 2 * MINUTE;

    private const SENT_MESSAGE = 'Si un compte existe pour cette adresse, un e-mail contenant un lien pour choisir un nouveau mot de passe vient d\'être envoyé. Le lien est valable une heure. Pensez à vérifier vos courriers indésirables.';

    private UserRepository $userRepository;
    private PasswordResetRepository $passwordResetRepository;

    public function __construct()
    {
        $this->userRepository = service('repository', 'User');
        $this->passwordResetRepository = service('repository', 'PasswordReset');
        helper('form');
    }

    /**
     * Form asking the e-mail address.
     */
    public function forgot()
    {
        if (SessionHelper::isUserConnected())
            return redirect()->to(base_url('/'));

        return view('pages/auth/forgot_password');
    }

    /**
     * Sends the e-mail with the reset link (if the address has an account).
     */
    public function sendLink()
    {
        $email = trim((string) $this->request->getPost('email'));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
            $this->session->setFlashdata('errors', ['L\'adresse e-mail n\'est pas valide.']);
            return redirect()->to(base_url('/auth/mot-de-passe-oublie'))->withInput();
        }

        $throttler = service('throttler');
        if ($throttler->check('password-reset-' . md5($this->request->getIPAddress()), self::MAX_REQUESTS_PER_IP, self::REQUESTS_PERIOD) === false) {
            $this->session->setFlashdata('errors', ['Trop de demandes. Réessayez dans quelques minutes.']);
            return redirect()->to(base_url('/auth/mot-de-passe-oublie'))->withInput();
        }

        $user = $this->userRepository->findOneBy(['email' => $email], BaseRepository::RESULT_AS_OBJECT);

        if ($user !== null && !$this->passwordResetRepository->hasRecentRequest((int) $user->id, self::DELAY_BETWEEN_EMAILS)) {
            $token = $this->passwordResetRepository->createToken((int) $user->id, $this->request->getIPAddress());
            $this->sendEmail($user->email, 'Réinitialisation de votre mot de passe', 'emails/password_reset', [
                'totem' => $user->totem,
                'link' => base_url('auth/reinitialiser/' . $token),
                'lifetime' => PasswordResetRepository::LIFETIME / 60,
            ]);
        }

        $this->session->setFlashdata('success', self::SENT_MESSAGE);
        return redirect()->to(base_url('/auth/mot-de-passe-oublie'));
    }

    /**
     * Form to choose the new password.
     */
    public function reset($token)
    {
        $reset = $this->passwordResetRepository->findValid((string) $token);

        // The token is in the url: it must not be sent to other sites through the "Referer" header
        $this->response->setHeader('Referrer-Policy', 'no-referrer');

        return view('pages/auth/reset_password', [
            'token' => $reset ? $token : null,
            'email' => $reset->email ?? null,
            'minLength' => self::PASSWORD_MIN_LENGTH,
            'maxLength' => self::PASSWORD_MAX_LENGTH,
        ]);
    }

    /**
     * Saves the new password.
     */
    public function update($token)
    {
        $reset = $this->passwordResetRepository->findValid((string) $token);
        if ($reset === null)
            return redirect()->to(base_url('/auth/reinitialiser/' . rawurlencode((string) $token)));

        $password = (string) $this->request->getPost('password');
        $confirmation = (string) $this->request->getPost('password_confirmation');

        $errors = [];
        if (strlen($password) < self::PASSWORD_MIN_LENGTH)
            $errors[] = 'Le mot de passe doit contenir au moins ' . self::PASSWORD_MIN_LENGTH . ' caractères.';
        if (strlen($password) > self::PASSWORD_MAX_LENGTH)
            $errors[] = 'Le mot de passe doit contenir au maximum ' . self::PASSWORD_MAX_LENGTH . ' caractères.';
        if ($password !== $confirmation)
            $errors[] = 'Les deux mots de passe ne sont pas identiques.';

        if ($errors) {
            $this->session->setFlashdata('errors', $errors);
            return redirect()->to(base_url('/auth/reinitialiser/' . $token));
        }

        $this->userRepository->update($reset->user_id, [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        // The link (and any other link sent before) can not be used anymore
        $this->passwordResetRepository->invalidateForUser((int) $reset->user_id);

        $this->sendEmail($reset->email, 'Votre mot de passe a été modifié', 'emails/password_changed', [
            'totem' => $reset->totem,
            'forgotLink' => base_url('auth/mot-de-passe-oublie'),
        ]);

        $this->session->setFlashdata('success', 'Votre mot de passe a été modifié. Vous pouvez maintenant vous connecter.');
        return redirect()->to(base_url('/auth/login'));
    }

    /**
     * Sends an HTML e-mail. A failure is logged but not shown: the visitor gets the same answer in any case.
     */
    private function sendEmail(string $to, string $subject, string $view, array $data)
    {
        $email = service('email');
        //Sender: "email.fromEmail" / "email.fromName" in the .env file
        $config = config('Email');
        $email->setFrom($config->fromEmail ?: 'noreply@gsgosselies.be', $config->fromName ?: 'Guides et Scouts de Gosselies');
        $email->setTo($to);
        $email->setSubject($subject);
        $html = view($view, $data + ['subject' => $subject]);
        $email->setMailType('html');
        $email->setMessage($html);
        //Text version for the mail clients that do not display HTML
        $text = preg_replace('#<head>.*?</head>#s', '', $html);
        $text = html_entity_decode(strip_tags(str_replace(['<br>', '</p>', '</tr>'], "\n", $text)), ENT_QUOTES, 'UTF-8');
        $email->setAltMessage(trim(preg_replace("/\n\s*\n+/", "\n\n", $text)));

        if (!$email->send(false))
            log_message('error', 'Password reset e-mail not sent to {to}: {debug}', ['to' => $to, 'debug' => $email->printDebugger(['headers'])]);
    }
}
