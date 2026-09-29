<?php

namespace Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', static function () {
    if (ENVIRONMENT !== 'testing') {
        if (ini_get('zlib.output_compression')) {
            throw FrameworkException::forEnabledZlibOutputCompression();
        }

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        ob_start(static fn ($buffer) => $buffer);
    }

    /*
     * --------------------------------------------------------------------
     * Debug Toolbar Listeners.
     * --------------------------------------------------------------------
     * If you delete, they will no longer be collected.
     */
    if (CI_DEBUG && ! is_cli()) {
        Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
        Services::toolbar()->respond();
    }
});

/*
 * --------------------------------------------------------------------
 * Failed and slow database queries
 * --------------------------------------------------------------------
 * In production (DBDebug disabled), a failed query only returns false: it is logged here so that the bug can be found.
 * CodeIgniter gives a failed query a duration of exactly 0 (same start and end time), whatever the database driver.
 */
Events::on('DBQuery', static function (\CodeIgniter\Database\Query $query) {
    $duration = (float) $query->getDuration(12);
    if ($duration === 0.0) {
        $error = \Config\Database::connect()->error();
        log_message('error', 'Requête SQL en échec : {error} — {sql}', [
            'error' => trim(($error['code'] ?? '') . ' ' . ($error['message'] ?? '')),
            'sql' => mb_substr($query->getQuery(), 0, 1000),
        ]);
    } elseif ($duration > 1) {
        log_message('warning', 'Requête SQL lente ({duration} s) : {sql}', ['duration' => number_format($duration, 2, ',', ''), 'sql' => mb_substr($query->getQuery(), 0, 1000)]);
    }
});
