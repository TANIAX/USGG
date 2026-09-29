<?php

namespace App\Libraries;

/**
 * Reads the log files of writable/logs for the page /admin/logs:
 * - app-YYYY-MM-DD.log: one JSON object per line (App\Log\JsonFileHandler),
 * - log-YYYY-MM-DD.log: former format of CodeIgniter ("LEVEL - date --> message", the message on several lines).
 */
class LogReader
{
    /**
     * Maximum number of entries returned (the most recent ones)
     */
    public const MAX_ENTRIES = 2000;

    /**
     * Levels from the most to the least serious
     */
    public const LEVELS = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'];

    private string $path;

    public function __construct(?string $path = null)
    {
        $this->path = rtrim($path ?? WRITEPATH . 'logs', '/') . '/';
    }

    /**
     * Days having logs, the most recent first.
     * @return array list of ['date' => 'YYYY-MM-DD', 'size' => bytes]
     */
    public function days(): array
    {
        $days = [];
        foreach ($this->files() as $file) {
            $date = $this->dateOf($file);
            $days[$date] = ($days[$date] ?? 0) + filesize($file);
        }
        krsort($days);

        return array_map(fn($date, $size) => ['date' => $date, 'size' => $size], array_keys($days), $days);
    }

    /**
     * Entries of the given days, the most recent first (at most MAX_ENTRIES).
     * @param string[] $dates days (YYYY-MM-DD)
     * @return array list of ['time', 'level', 'message', 'request', 'method', 'url', 'user', 'ip', 'agent']
     */
    public function entries(array $dates): array
    {
        $entries = [];
        foreach ($this->files() as $file) {
            if (!in_array($this->dateOf($file), $dates, true))
                continue;
            $entries = array_merge($entries, str_starts_with(basename($file), 'app-') ? $this->readJson($file) : $this->readLegacy($file));
        }

        usort($entries, fn($a, $b) => strcmp($b['time'], $a['time']));
        return array_slice($entries, 0, self::MAX_ENTRIES);
    }

    /**
     * Path of the file(s) of a day, for the download.
     * @return string[]
     */
    public function filesOf(string $date): array
    {
        return array_values(array_filter($this->files(), fn($file) => $this->dateOf($file) === $date));
    }

    private function files(): array
    {
        $files = array_merge(glob($this->path . 'app-*.log') ?: [], glob($this->path . 'log-*.log') ?: []);
        return array_values(array_filter($files, fn($file) => preg_match('/^(app|log)-\d{4}-\d{2}-\d{2}\.log$/', basename($file))));
    }

    private function dateOf(string $file): string
    {
        return substr(basename($file), -14, 10);
    }

    private function readJson(string $file): array
    {
        $entries = [];
        foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
            $entry = json_decode($line, true);
            if (is_array($entry) && isset($entry['time'], $entry['level']))
                $entries[] = $entry + ['message' => ''];
        }
        return $entries;
    }

    private function readLegacy(string $file): array
    {
        $entries = [];
        foreach (file($file, FILE_IGNORE_NEW_LINES) ?: [] as $line) {
            if (preg_match('/^([A-Z]+) - (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}) --> (.*)$/', $line, $match))
                $entries[] = ['time' => $match[2], 'level' => strtolower($match[1]), 'message' => $match[3], 'legacy' => true];
            elseif ($entries)
                $entries[count($entries) - 1]['message'] .= "\n" . $line;
        }
        return $entries;
    }
}
