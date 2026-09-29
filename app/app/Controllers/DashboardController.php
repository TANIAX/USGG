<?php

namespace App\Controllers;

use App\Helpers\GalleryHelper;
use App\Helpers\NavigationHelper;
use App\Helpers\RegistrationHelper;
use App\Helpers\SessionHelper;
use App\Libraries\LogReader;

/**
 * Home of the administration (/admin): what is waiting, and the tools allowed by the roles of the account.
 */
class DashboardController extends BaseController
{
    /**
     * Statuses of the registration requests still to handle
     */
    private const OPEN_STATUSES = ['new', 'contacted', 'waiting', 'trial'];

    public function index()
    {
        $roles = SessionHelper::getUserConnected()->getRolesAsStrings();
        $allowed = fn(string $href) => (bool) array_filter(NavigationHelper::adminLinks($roles), fn($link) => $link['href'] === $href);
        $cards = [];

        $events = service('repository', 'Event')->getUpcoming(0, 3);
        $cards[] = [
            'label' => 'Prochains événements',
            'value' => count($events['events']) . ($events['has_more'] ? '+' : ''),
            'href' => '/admin/agenda',
            'icon' => 'calendar-days',
            'items' => array_map(fn($event) => \App\Helpers\DateHelper::french($event->start_at, false) . ' · ' . $event->title, $events['events']),
        ];

        if ($allowed('/admin/inscriptions')) {
            $counts = array_count_values(array_column(service('repository', 'Registration')->getForAdmin(GalleryHelper::getManageableBranches()), 'status'));
            $open = array_sum(array_intersect_key($counts, array_flip(self::OPEN_STATUSES)));
            $cards[] = [
                'label' => 'Inscriptions à traiter',
                'value' => $open,
                'href' => '/admin/inscriptions',
                'icon' => 'login',
                'highlight' => ($counts['new'] ?? 0) > 0,
                'items' => array_map(fn($status) => RegistrationHelper::statusLabel($status) . ' : ' . $counts[$status], array_values(array_filter(self::OPEN_STATUSES, fn($status) => isset($counts[$status])))),
            ];
        }

        if ($allowed('/admin/messages')) {
            $unread = service('repository', 'ContactMessage')->countUnread();
            $cards[] = ['label' => 'Messages non lus', 'value' => $unread, 'href' => '/admin/messages', 'icon' => 'envelope', 'highlight' => $unread > 0, 'items' => []];
        }

        if ($allowed('/admin/newsletter')) {
            $cards[] = [
                'label' => 'Abonnés à la newsletter',
                'value' => count(service('repository', 'Newsletter')->getRecipients()),
                'href' => '/admin/newsletter',
                'icon' => 'envelope',
                'items' => [],
            ];
        }

        if ($allowed('/admin/logs')) {
            $reader = new LogReader();
            $dates = array_slice(array_column($reader->days(), 'date'), 0, 7);
            $since = date('Y-m-d', strtotime('-6 days'));
            $errors = array_filter($reader->entries(array_filter($dates, fn($date) => $date >= $since)),
                fn($entry) => in_array($entry['level'], ['emergency', 'alert', 'critical', 'error'], true));
            $cards[] = [
                'label' => 'Erreurs (7 derniers jours)',
                'value' => count($errors),
                'href' => '/admin/logs',
                'icon' => 'document',
                'highlight' => count($errors) > 0,
                'items' => array_map(fn($entry) => mb_strimwidth($entry['message'], 0, 90, '…'), array_slice(array_values($errors), 0, 3)),
            ];
        }

        $history = [];
        if ($allowed('/admin/historique')) {
            $history = \Config\Database::connect()->table('audit_log')
                        ->select('audit_log.*, user.firstname, user.totem')
                        ->join('user', 'user.id = audit_log.user_id', 'left')
                        ->orderBy('audit_log.id', 'DESC')
                        ->limit(8)
                        ->get()
                        ->getResultObject();
        }

        $groups = [];
        foreach (NavigationHelper::adminLinks($roles) as $link)
            $groups[$link['group']][] = $link;

        return view('pages/admin/dashboard/index', [
            'cards' => $cards,
            'groups' => $groups,
            'history' => $history,
            'user' => SessionHelper::getUserConnected(),
        ]);
    }
}
