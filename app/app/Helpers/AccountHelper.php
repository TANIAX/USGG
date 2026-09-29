<?php

namespace App\Helpers;

use App\Repositories\PasswordResetRepository;

/**
 * Creation of accounts by an administrator: the account gets an unknown random password
 * and the person chooses their own with a link sent by e-mail (valid PasswordResetRepository::INVITATION_LIFETIME).
 *
 * @author Guillaume Cornez
 */
class AccountHelper
{
    /**
     * Creates an account with the base role "user" and sends the invitation.
     *
     * @param array $data Columns of the user table (email, firstname, name, totem, phone, user_type_id, picture...)
     * @param string|null $reason e.g. "en tant que responsable de section" (shown in the e-mail)
     * @return array [int $userId, bool $emailSent]
     */
    public static function createAndInvite(array $data, ?string $reason = null)
    {
        $data['password'] = password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT);
        $userId = service('repository', 'User')->createAccount($data);

        return [$userId, self::sendInvitation($userId, $data['email'], ($data['totem'] ?? '') ?: $data['firstname'], $reason)];
    }

    /**
     * (Re)sends the e-mail with the link to choose a password. The previous links of the account are cancelled.
     *
     * @return bool false if the e-mail could not be sent
     */
    public static function sendInvitation(int $userId, string $email, string $name, ?string $reason = null)
    {
        $token = service('repository', 'PasswordReset')->createToken($userId, service('request')->getIPAddress(), PasswordResetRepository::INVITATION_LIFETIME);

        return MailHelper::send($email, 'Votre compte sur le site des Guides et Scouts de Gosselies', 'emails/account_created', [
            'name' => $name,
            'email' => $email,
            'reason' => $reason,
            'link' => base_url('auth/reinitialiser/' . $token),
            'days' => PasswordResetRepository::INVITATION_LIFETIME / 86400,
            'forgotLink' => base_url('auth/mot-de-passe-oublie'),
        ]);
    }
}
