<?php

namespace App\Helpers;

use DateTime;

/**
 * A helper class for handling date and time operations.
 *
 * @author Guillaume cornez
 */
class DateHelper
{
    /**
     * Validates a date and time string against a given format.
     *
     * @param string $dateStr The date and time string to validate.
     * @param string $format  The format to validate the date and time string against.
     *
     * @return bool Returns true if the date and time string is valid, false otherwise.
     */
    public static function validateDateTime($dateStr, $format)
    {
        date_default_timezone_set('UTC');
        $date = DateTime::createFromFormat($format, $dateStr);
        return $date && ($date->format($format) === $dateStr);
    }

    /**
     * Formats a date and time string from one format to another.
     *
     * @param string $dateStr   The date and time string to format.
     * @param string $format    The format of the input date and time string.
     * @param string $formatTo  The format to convert the date and time string to.
     *
     * @return string The formatted date and time string.
     */
    public static function formatDate($dateStr, $format, $formatTo)
    {
        date_default_timezone_set('UTC');
        $date = DateTime::createFromFormat($format, $dateStr);
        return $date->format($formatTo);
    }

    private const DAYS = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
    private const MONTHS = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

    /**
     * Date in french, without the intl extension: "samedi 3 octobre 2026" (+ " à 14:00" with the time).
     */
    public static function french(string $dateTime, bool $withTime = false): string
    {
        $timestamp = strtotime($dateTime);
        $text = self::DAYS[(int) date('w', $timestamp)] . ' ' . date('j', $timestamp) . ' ' . self::MONTHS[(int) date('n', $timestamp) - 1] . ' ' . date('Y', $timestamp);
        return $withTime ? $text . ' à ' . date('H:i', $timestamp) : $text;
    }

    /**
     * Period of an agenda event in french (same wording as formatEventPeriod() of script.js, simplified).
     */
    public static function frenchPeriod($event): string
    {
        $sameDay = substr($event->start_at, 0, 10) === substr($event->end_at, 0, 10);
        if ($event->all_day)
            return ucfirst(self::french($event->start_at)) . ($sameDay ? ' (toute la journée)' : ' → ' . self::french($event->end_at));
        if ($sameDay)
            return ucfirst(self::french($event->start_at)) . ', de ' . date('H:i', strtotime($event->start_at)) . ' à ' . date('H:i', strtotime($event->end_at));
        return 'Du ' . self::french($event->start_at, true) . ' au ' . self::french($event->end_at, true);
    }
}
