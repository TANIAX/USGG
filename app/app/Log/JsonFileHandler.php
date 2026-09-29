<?php

namespace App\Log;

use App\Libraries\RequestContext;
use CodeIgniter\Log\Handlers\BaseHandler;
use Throwable;

/**
 * Writes the logs in writable/logs/app-YYYY-MM-DD.log, one JSON object per line:
 * {"time", "level", "message", "request", "method", "url", "user", "ip", "agent"}.
 * The files older than "retentionDays" are deleted (checked when the file of the day is created).
 * They are read by the page /admin/logs (App\Libraries\LogReader).
 */
class JsonFileHandler extends BaseHandler
{
    private string $path;
    private int $retentionDays;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->path = rtrim(($config['path'] ?? '') ?: WRITEPATH . 'logs/', '/') . '/';
        $this->retentionDays = (int) ($config['retentionDays'] ?? 90);
    }

    public function handle($level, $message): bool
    {
        // A log must never break the page: any problem here is ignored
        try {
            $file = $this->path . 'app-' . date('Y-m-d') . '.log';
            $newFile = !is_file($file);

            $entry = ['time' => date('Y-m-d H:i:s'), 'level' => $level, 'message' => mb_substr((string) $message, 0, 20000)] + RequestContext::toArray();
            $line = json_encode($entry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE) . "\n";

            if (@file_put_contents($file, $line, FILE_APPEND | LOCK_EX) === false)
                return false;

            if ($newFile) {
                @chmod($file, 0640);
                $this->deleteOldFiles();
            }
        } catch (Throwable $exception) {
            return false;
        }

        return true;
    }

    private function deleteOldFiles(): void
    {
        $limit = date('Y-m-d', strtotime('-' . $this->retentionDays . ' days'));
        $files = array_merge(glob($this->path . 'app-*.log') ?: [], glob($this->path . 'log-*.log') ?: []);
        foreach ($files as $file) {
            if (preg_match('/-(\d{4}-\d{2}-\d{2})\./', basename($file), $match) && $match[1] < $limit)
                @unlink($file);
        }
    }
}
