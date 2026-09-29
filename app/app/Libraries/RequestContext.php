<?php

namespace App\Libraries;

use App\Helpers\SessionHelper;

/**
 * Information on the current request, added to every log line: identifier of the request (also shown on the error page
 * and sent in the "X-Request-Id" header, to find the logs of an error reported by a user), method, url, connected user
 * and IP address (anonymised: the last part is removed).
 */
class RequestContext
{
    private static ?string $id = null;

    /**
     * Identifier of the current request (8 hexadecimal characters).
     */
    public static function id(): string
    {
        return self::$id ??= bin2hex(random_bytes(4));
    }

    public static function toArray(): array
    {
        if (is_cli())
            return ['request' => self::id(), 'cli' => implode(' ', array_slice($_SERVER['argv'] ?? [], 0, 3))];

        return [
            'request' => self::id(),
            'method' => $_SERVER['REQUEST_METHOD'] ?? null,
            'url' => mb_substr($_SERVER['REQUEST_URI'] ?? '', 0, 300),
            'user' => self::userId(),
            'ip' => self::anonymizedIp($_SERVER['REMOTE_ADDR'] ?? ''),
            'agent' => mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 200) ?: null,
        ];
    }

    /**
     * Id of the connected user, read without starting the session (the logger must not have side effects).
     */
    public static function userId(): ?int
    {
        if (session_status() !== PHP_SESSION_ACTIVE)
            return null;

        $user = $_SESSION[SessionHelper::USER_CONNECTED_SESSION_KEY] ?? null;
        return is_object($user) && method_exists($user, 'getId') ? (int) $user->getId() : null;
    }

    /**
     * IPv4: 192.168.1.x -> 192.168.1.0 ; IPv6: only the first 3 groups are kept.
     */
    public static function anonymizedIp(string $ip): ?string
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            return preg_replace('/\.\d+$/', '.0', $ip);
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
            return implode(':', array_slice(explode(':', $ip), 0, 3)) . '::';
        return null;
    }
}
