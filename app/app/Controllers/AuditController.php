<?php

namespace App\Controllers;

use App\Helpers\AuditHelper;

/**
 * History of the actions of the administrators (super admin).
 */
class AuditController extends BaseController
{
    /**
     * Number of actions displayed (the most recent)
     */
    private const LIMIT = 1000;

    public function index()
    {
        $entries = \Config\Database::connect()->table('audit_log')
                    ->select('audit_log.*, user.firstname, user.name AS lastname, user.totem, user.email')
                    ->join('user', 'user.id = audit_log.user_id', 'left')
                    ->orderBy('audit_log.id', 'DESC')
                    ->limit(self::LIMIT)
                    ->get()
                    ->getResultObject();

        $entries = array_map(fn($entry) => [
            'id' => (int) $entry->id,
            'time' => $entry->created_at,
            'action' => $entry->action,
            'subject' => $entry->subject,
            'label' => $entry->label,
            'url' => $entry->url,
            'user_id' => $entry->user_id !== null ? (int) $entry->user_id : null,
            'user' => $entry->totem ?: trim($entry->firstname . ' ' . $entry->lastname) ?: ($entry->email ?? 'Compte supprimé'),
        ], $entries);

        return view('pages/admin/audit/index', [
            'entries' => $this->toJson($entries),
            'actions' => AuditHelper::ACTIONS,
            'subjects' => array_values(array_unique(array_column($entries, 'subject'))),
            'limit' => self::LIMIT,
        ]);
    }
}
