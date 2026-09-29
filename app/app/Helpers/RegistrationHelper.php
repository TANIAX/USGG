<?php

namespace App\Helpers;

/**
 * Registration requests: statuses and link with the unit (phase of the registration).
 */
class RegistrationHelper
{
    /**
     * Statuses of a request, in the order of the handling: [label, colour of components/badge]
     */
    public const STATUSES = [
        'new' => ['Nouvelle', 'indigo'],
        'contacted' => ['Famille contactée', 'purple'],
        'waiting' => ['Liste d\'attente', 'amber'],
        'trial' => ['Réunion d\'essai', 'outline'],
        'registered' => ['Inscrit', 'green'],
        'refused' => ['Clôturée', 'gray'],
    ];

    /**
     * Link with the unit: [label, phase of the registration]
     */
    public const RELATIONS = [
        'sibling' => ['Un frère ou une sœur est déjà inscrit(e) dans l\'une des deux unités', 1],
        'former_member' => ['Un parent est un ancien membre actif de l\'unité', 1],
        'none' => ['Aucun des deux', 2],
    ];

    public static function phase(string $relation): int
    {
        return self::RELATIONS[$relation][1] ?? 2;
    }

    public static function statusLabel(string $status): string
    {
        return self::STATUSES[$status][0] ?? $status;
    }

    /**
     * Age in years at a given date (default: today).
     */
    public static function age(string $birthdate, ?string $at = null): int
    {
        return (new \DateTime($birthdate))->diff(new \DateTime($at ?? 'today'))->y;
    }
}
