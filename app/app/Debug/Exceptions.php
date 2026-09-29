<?php

namespace App\Debug;

use CodeIgniter\Debug\Exceptions as BaseExceptions;
use Throwable;

/**
 * Exception handler of CodeIgniter, which does not log the "404 not found" (Config\Exceptions::$ignoreCodes).
 * A 404 reached from a page of the site is a broken link: it is logged as a notice.
 * The reference of the request (shown on the error page) is also sent in the "X-Request-Id" header.
 */
class Exceptions extends BaseExceptions
{
    public function exceptionHandler(Throwable $exception)
    {
        // Reference of the error page (the after filters are not run after an exception)
        if (!headers_sent())
            header('X-Request-Id: ' . \App\Libraries\RequestContext::id());

        if ($exception->getCode() === 404)
            self::logBrokenLink();

        parent::exceptionHandler($exception);
    }

    private static function logBrokenLink(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        $host = $_SERVER['HTTP_HOST'] ?? '';
        // Only the links of the site: the 404 of robots and old external links are not bugs of the site
        if ($referer === '' || $host === '' || parse_url($referer, PHP_URL_HOST) !== parse_url('http://' . $host, PHP_URL_HOST))
            return;

        log_message('notice', 'Lien cassé : page introuvable (404), atteinte depuis {referer}', ['referer' => parse_url($referer, PHP_URL_PATH) . (parse_url($referer, PHP_URL_QUERY) ? '?' . parse_url($referer, PHP_URL_QUERY) : '')]);
    }
}
