<?php

namespace App\Helpers;

/**
 * The two units (guides, scouts) and their sections, defined once: menus, home page, presentation and staff pages.
 * The slug of a section is its anchor on the presentation page (/guide#nutons) and its slug in the database.
 */
class UnitHelper
{
    public const UNITS = [
        'guide' => [
            'name' => 'Guides',
            'label' => 'l’unité guide',
            'url' => '/guide',
            'logo' => 'assets/img/logo-guide.png',
            'sections' => [
                'nutons' => ['name' => 'Nutons', 'ages' => '5 à 7 ans', 'logo' => 'assets/img/logo-nutons.png'],
                'lutins' => ['name' => 'Lutins', 'ages' => '8 à 11 ans', 'logo' => 'assets/img/logo-lutins.png'],
                'aventures' => ['name' => 'Aventures', 'ages' => '11 à 15 ans', 'logo' => 'assets/img/logo-aventures.png'],
                'horizons' => ['name' => 'Horizons', 'ages' => '16 à 18 ans', 'logo' => 'assets/img/logo-horizons.png'],
            ],
        ],
        'scout' => [
            'name' => 'Scouts',
            'label' => 'l’unité scoute',
            'url' => '/scout',
            'logo' => 'assets/img/logo-scout.png',
            'sections' => [
                'baladins' => ['name' => 'Baladins', 'ages' => '6 à 8 ans', 'logo' => 'assets/img/logo-baladins.png'],
                'louveteaux' => ['name' => 'Louveteaux', 'ages' => '8 à 12 ans', 'logo' => 'assets/img/logo-louveteaux.png'],
                'eclaireurs' => ['name' => 'Éclaireurs', 'ages' => '12 à 16 ans', 'logo' => 'assets/img/logo-eclaireurs.png'],
                'pionniers' => ['name' => 'Pionniers', 'ages' => '16 à 18 ans', 'logo' => 'assets/img/logo-pionniers.png'],
            ],
        ],
    ];

    /**
     * Sections of a unit, with their slug and the url of their presentation.
     * @return array list of ['slug', 'name', 'ages', 'logo', 'href']
     */
    public static function sections(string $unit): array
    {
        $sections = [];
        foreach (self::UNITS[$unit]['sections'] as $slug => $section) {
            $sections[] = $section + ['slug' => $slug, 'href' => self::UNITS[$unit]['url'] . '#' . $slug];
        }
        return $sections;
    }
}
