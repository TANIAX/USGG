<?php

namespace App\Helpers;

/**
 * Helpers for the logs (see also App\Libraries\RequestContext and App\Log\JsonFileHandler).
 */
class LogHelper
{
    /**
     * E-mail address partially hidden in the logs: "jean.dupont@gmail.com" -> "je***@gmail.com".
     */
    public static function maskEmail(?string $email): string
    {
        $email = trim((string) $email);
        if (!str_contains($email, '@'))
            return $email === '' ? '(vide)' : '***';

        [$name, $domain] = explode('@', $email, 2);
        return mb_substr($name, 0, 2) . '***@' . $domain;
    }

    /**
     * Logs an exception caught by the application (the user sees a message, the details are in the logs).
     */
    public static function exception(string $context, \Throwable $exception, string $level = 'error'): void
    {
        log_message($level, '{context} : {message} ({exFile}:{exLine})', [
            'context' => $context,
            'message' => $exception->getMessage(),
            'exFile' => str_replace(ROOTPATH, '', $exception->getFile()),
            'exLine' => $exception->getLine(),
        ]);
    }
}
