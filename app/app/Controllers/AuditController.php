<?php

namespace App\Controllers;

use App\Helpers\AuditHelper;
use App\Libraries\ListQuery;

/**
 * History of the actions of the administrators (super admin), paginated by the server.
 */
class AuditController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $subjects = array_column($db->table('audit_log')->distinct()->select('subject')->orderBy('subject')->get()->getResultArray(), 'subject');
        $people = [];
        foreach ($db->table('audit_log')->distinct()->select('audit_log.user_id, user.firstname, user.name, user.totem, user.email')
                     ->join('user', 'user.id = audit_log.user_id', 'left')->where('audit_log.user_id IS NOT NULL', null, false)->get()->getResultObject() as $person) {
            $people[(string) $person->user_id] = self::personName($person);
        }
        asort($people);

        $list = ListQuery::fromRequest($this->request, [
            'subject' => array_merge([''], $subjects),
            'user' => array_merge([''], array_keys($people)),
        ]);
        $builder = $db->table('audit_log')
                    ->select('audit_log.*, user.firstname, user.name AS lastname, user.totem, user.email')
                    ->join('user', 'user.id = audit_log.user_id', 'left')
                    ->orderBy('audit_log.id', 'DESC');
        if ($list->filter('subject') !== '')
            $builder->where('audit_log.subject', $list->filter('subject'));
        if ($list->filter('user') !== '')
            $builder->where('audit_log.user_id', (int) $list->filter('user'));
        $list->search($builder, ['audit_log.label', 'audit_log.subject', 'user.firstname', 'user.name', 'user.totem']);

        $result = $list->paginate($builder, fn($entry) => [
            'id' => (int) $entry->id,
            'time' => $entry->created_at,
            'action' => $entry->action,
            'subject' => $entry->subject,
            'label' => $entry->label,
            'url' => $entry->url,
            'user' => self::personName((object) ['firstname' => $entry->firstname, 'name' => $entry->lastname, 'totem' => $entry->totem, 'email' => $entry->email]),
        ]);

        return $this->listResponse('pages/admin/audit/index', [
            'actions' => AuditHelper::ACTIONS,
            'subjects' => $subjects,
            'people' => $people,
        ], $result);
    }

    private static function personName($person): string
    {
        return $person->totem ?: trim(($person->firstname ?? '') . ' ' . ($person->name ?? '')) ?: ($person->email ?? 'Compte supprimé');
    }
}
