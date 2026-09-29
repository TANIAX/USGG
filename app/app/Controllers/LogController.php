<?php

namespace App\Controllers;

use App\Libraries\LogReader;

/**
 * Logs of the application (super admin): errors and notable events, to find the bugs of the production site.
 */
class LogController extends BaseController
{
    /**
     * Periods of several days that can be displayed (in addition to a single day)
     */
    private const PERIODS = ['7j' => 7, '30j' => 30, '90j' => 90];

    private LogReader $reader;

    public function __construct()
    {
        $this->reader = new LogReader();
    }

    public function index()
    {
        $days = $this->reader->days();
        $period = (string) $this->request->getGet('periode');

        if (isset(self::PERIODS[$period])) {
            $limit = date('Y-m-d', strtotime('-' . (self::PERIODS[$period] - 1) . ' days'));
            $dates = array_values(array_filter(array_column($days, 'date'), fn($date) => $date >= $limit));
        } else {
            $period = in_array($period, array_column($days, 'date'), true) ? $period : '7j';
            $dates = $period === '7j' ? array_values(array_filter(array_column($days, 'date'), fn($date) => $date >= date('Y-m-d', strtotime('-6 days')))) : [$period];
        }

        $entries = $this->reader->entries($dates);

        return view('pages/admin/log/index', [
            'days' => $days,
            'period' => $period,
            'periods' => array_keys(self::PERIODS),
            'entries' => $this->toJson($entries),
            'truncated' => count($entries) >= LogReader::MAX_ENTRIES,
        ]);
    }

    /**
     * Raw file(s) of a day, to keep them or send them to a developer.
     */
    public function download($date)
    {
        $files = preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $date) ? $this->reader->filesOf($date) : [];
        if (!$files)
            return $this->redirectWithErrors('/admin/logs', 'Aucun journal pour ce jour.');

        $content = implode("\n", array_map(fn($file) => file_get_contents($file), $files));
        return $this->response->download('journal-' . $date . '.log', $content);
    }
}
