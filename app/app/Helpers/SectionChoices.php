<?php

namespace App\Helpers;

/**
 * Sections that a child can join (all but "Unité"), for the select fields: ['Guides' => [id => 'Nutons (5 à 7 ans)'], 'Scouts' => [...]].
 */
class SectionChoices
{
    private const UNITS = ['GUIDE' => 'guide', 'SCOUTE' => 'scout'];

    public static function grouped(): array
    {
        $choices = [];
        foreach (service('repository', 'Section')->getAllOrdered() as $section) {
            $unit = self::UNITS[$section->branch] ?? null;
            if ($unit === null)
                continue;
            $ages = UnitHelper::UNITS[$unit]['sections'][$section->slug]['ages'] ?? null;
            $choices[UnitHelper::UNITS[$unit]['name']][(int) $section->id] = $section->name . ($ages ? ' (' . $ages . ')' : '');
        }
        return $choices;
    }

    /**
     * Ids of the sections a child can join.
     */
    public static function ids(): array
    {
        $ids = [];
        foreach (self::grouped() as $sections) {
            $ids = array_merge($ids, array_keys($sections));
        }
        return $ids;
    }
}
