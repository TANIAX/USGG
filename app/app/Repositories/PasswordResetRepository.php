<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

/**
 * Tokens of the "forgotten password" feature.
 * Only the SHA-256 of a token is stored, a token can be used once and expires after PasswordResetRepository::LIFETIME seconds.
 *
 * @author Guillaume Cornez
 */
class PasswordResetRepository extends BaseRepository
{
    public const LIFETIME = 3600;

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('password_reset');
    }

    /**
     * Creates a token for a user (the previous unused tokens of the user are cancelled).
     *
     * @return string The token to send by e-mail
     */
    public function createToken(int $userId, ?string $ipAddress)
    {
        $this->invalidateForUser($userId);

        $token = bin2hex(random_bytes(32));
        $this->builder->insert([
            'user_id' => $userId,
            'token_hash' => hash('sha256', $token),
            'expires_at' => date('Y-m-d H:i:s', time() + self::LIFETIME),
            'ip_address' => $ipAddress,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $token;
    }

    /**
     * Returns the reset request of a token if it can still be used (not used, not expired).
     *
     * @return object|null (id, user_id, email, totem)
     */
    public function findValid(string $token)
    {
        if (!preg_match('/^[0-9a-f]{64}$/', $token))
            return null;

        return $this->builder
                    ->select('password_reset.id, password_reset.user_id, user.email, user.totem')
                    ->join('user', 'user.id = password_reset.user_id')
                    ->where('password_reset.token_hash', hash('sha256', $token))
                    ->where('password_reset.used_at', null)
                    ->where('password_reset.expires_at >', date('Y-m-d H:i:s'))
                    ->where('user.exists', true)
                    ->get()
                    ->getRowObject();
    }

    /**
     * Was a token created for this user less than $seconds ago? (avoids sending e-mails in a loop)
     */
    public function hasRecentRequest(int $userId, int $seconds)
    {
        return $this->builder
                    ->where('user_id', $userId)
                    ->where('created_at >', date('Y-m-d H:i:s', time() - $seconds))
                    ->countAllResults() > 0;
    }

    /**
     * Marks every unused token of a user as used.
     */
    public function invalidateForUser(int $userId)
    {
        return $this->builder
                    ->where('user_id', $userId)
                    ->where('used_at', null)
                    ->update(['used_at' => date('Y-m-d H:i:s')]);
    }
}
