<?php

namespace App\Repositories;

/**
 * Newsletter: subscribers (table newsletter_subscriber) and sendings (table newsletter_sending).
 */
class NewsletterRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('newsletter_subscriber');
    }

    public function findByEmail(string $email)
    {
        return $this->db->table('newsletter_subscriber')->where('email', $email)->get()->getRowObject();
    }

    public function findByToken(string $token)
    {
        return preg_match('/^[0-9a-f]{32}$/', $token) ? $this->db->table('newsletter_subscriber')->where('token', $token)->get()->getRowObject() : null;
    }

    /**
     * New subscription, or subscription started again (new token, waiting for the confirmation).
     * @return string token of the confirmation link
     */
    public function subscribe(string $email): string
    {
        $token = bin2hex(random_bytes(16));
        $existing = $this->findByEmail($email);
        if ($existing)
            $this->db->table('newsletter_subscriber')->where('id', $existing->id)->update(['token' => $token, 'confirmed_at' => null, 'unsubscribed_at' => null]);
        else
            $this->db->table('newsletter_subscriber')->insert(['email' => $email, 'token' => $token, 'created_at' => date('Y-m-d H:i:s')]);
        return $token;
    }

    public function confirm(int $id): void
    {
        $this->db->table('newsletter_subscriber')->where('id', $id)->update(['confirmed_at' => date('Y-m-d H:i:s'), 'unsubscribed_at' => null]);
    }

    public function unsubscribe(int $id): void
    {
        $this->db->table('newsletter_subscriber')->where('id', $id)->update(['unsubscribed_at' => date('Y-m-d H:i:s')]);
    }

    public function remove(int $id): void
    {
        $this->db->table('newsletter_subscriber')->where('id', $id)->delete();
    }

    /**
     * Statuses of the list: active (confirmed), pending (not confirmed), unsubscribed, '' (all)
     */
    private const STATUSES = ['active', 'pending', 'unsubscribed', ''];

    /**
     * Query of the subscribers of a status for the paginated list of the administration, the most recent first.
     */
    public function adminQuery(string $status = '')
    {
        $builder = $this->db->table('newsletter_subscriber')->select('id, email, confirmed_at, unsubscribed_at, created_at');
        if ($status === 'active')
            $builder->where('confirmed_at IS NOT NULL', null, false)->where('unsubscribed_at', null);
        elseif ($status === 'pending')
            $builder->where('confirmed_at', null)->where('unsubscribed_at', null);
        elseif ($status === 'unsubscribed')
            $builder->where('unsubscribed_at IS NOT NULL', null, false);

        return $builder->orderBy('created_at', 'DESC')->orderBy('id', 'DESC');
    }

    /**
     * Number of subscribers of each status: [status => count]
     */
    public function countByStatus(): array
    {
        $counts = [];
        foreach (self::STATUSES as $status) {
            $counts[$status] = $this->adminQuery($status)->countAllResults();
        }
        return $counts;
    }

    public static function cast($subscriber)
    {
        $subscriber->id = (int) $subscriber->id;
        $subscriber->status = $subscriber->unsubscribed_at ? 'unsubscribed' : ($subscriber->confirmed_at ? 'active' : 'pending');
        return $subscriber;
    }

    /**
     * Subscribers receiving the newsletter (confirmed, not unsubscribed), with their token (unsubscription link).
     */
    public function getRecipients(): array
    {
        return $this->db->table('newsletter_subscriber')
                    ->select('email, token')
                    ->where('confirmed_at IS NOT NULL', null, false)
                    ->where('unsubscribed_at', null)
                    ->get()
                    ->getResultObject();
    }

    public function addSending(array $data): void
    {
        $this->db->table('newsletter_sending')->insert($data + ['created_at' => date('Y-m-d H:i:s')]);
    }

    public function getSendings(int $limit = 10): array
    {
        return $this->db->table('newsletter_sending')
                    ->select('newsletter_sending.*, user.firstname, user.totem')
                    ->join('user', 'user.id = newsletter_sending.sent_by', 'left')
                    ->orderBy('newsletter_sending.created_at', 'DESC')
                    ->limit($limit)
                    ->get()
                    ->getResultObject();
    }
}
