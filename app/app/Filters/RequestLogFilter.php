<?php

namespace App\Filters;

use App\Libraries\RequestContext;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Global filter: identifier of the request in the "X-Request-Id" header, logs of the slow requests and of the server
 * errors answered without exception (the exceptions are logged by the exception handler).
 */
class RequestLogFilter implements FilterInterface
{
    /**
     * Duration (seconds) above which a request is logged as slow
     */
    public const SLOW_REQUEST = 3;

    public function before(RequestInterface $request, $arguments = null)
    {
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader('X-Request-Id', RequestContext::id());

        if ($response->getStatusCode() >= 500)
            log_message('error', 'Réponse {status} envoyée', ['status' => $response->getStatusCode()]);

        $duration = microtime(true) - ($_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true));
        if ($duration > self::SLOW_REQUEST)
            log_message('warning', 'Requête lente : {duration} s', ['duration' => number_format($duration, 1, ',', '')]);
    }
}
