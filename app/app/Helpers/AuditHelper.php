<?php

namespace App\Helpers;

/**
 * History of the actions of the administrators (table audit_log, page /admin/historique).
 */
class AuditHelper
{
    public const ACTIONS = [
        'created' => 'Création',
        'updated' => 'Modification',
        'deleted' => 'Suppression',
        'sent' => 'Envoi',
    ];

    /**
     * Records an action of the connected user. A problem here never blocks the action itself.
     *
     * @param string $action created, updated, deleted, sent
     * @param string $subject kind of item: "Événement", "Document", "Compte"...
     * @param string $label what was done / on what (e.g. the title of the event)
     * @param string|null $url page of the item in the administration
     */
    public static function log(string $action, string $subject, string $label, ?string $url = null): void
    {
        try {
            $user = SessionHelper::getUserConnected();
            \Config\Database::connect()->table('audit_log')->insert([
                'user_id' => $user ? $user->getId() : null,
                'action' => $action,
                'subject' => mb_substr($subject, 0, 50),
                'label' => mb_substr($label, 0, 255),
                'url' => $url,
                // Local time of the site (the default value of the database would be UTC with SQLite)
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $exception) {
            LogHelper::exception('Historique non enregistré', $exception, 'warning');
        }
    }
}
