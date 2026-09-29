<?php

namespace App\Helpers;

/**
 * The two units (guides, scouts). Their sections are in the database (table section, managed in /admin/sections) and are
 * used by the menus, the home page, the presentation and staff pages. The slug of a section is its anchor on the
 * presentation page (/guide#nutons).
 */
class UnitHelper
{
    public const UNITS = [
        'guide' => [
            'name' => 'Guides',
            'label' => 'l’unité guide',
            'url' => '/guide',
            'logo' => 'assets/img/logo-guide.png',
            'branch' => 'GUIDE',
        ],
        'scout' => [
            'name' => 'Scouts',
            'label' => 'l’unité scoute',
            'url' => '/scout',
            'logo' => 'assets/img/logo-scout.png',
            'branch' => 'SCOUTE',
        ],
    ];

    private static array $cache = [];

    /**
     * Active sections of a unit (managed in /admin/sections), with the url of their presentation.
     * @return array list of ['id', 'slug', 'name', 'ages', 'logo', 'color', 'href', 'title', 'group', 'subtitle', 'paragraphs']
     */
    public static function sections(string $unit): array
    {
        return self::$cache[$unit] ??= array_map(fn($section) => [
            'id' => (int) $section->id,
            'slug' => $section->slug,
            'name' => $section->name,
            'ages' => $section->ages,
            'logo' => $section->logo,
            'color' => $section->color,
            'href' => self::UNITS[$unit]['url'] . '#' . $section->slug,
            'title' => $section->title ?: $section->name,
            'group' => $section->group_name,
            'subtitle' => implode(' · ', array_filter([$section->group_name, $section->ages])),
            'paragraphs' => self::paragraphs($section->description),
        ], service('repository', 'Section')->getPresentations(self::UNITS[$unit]['branch']));
    }

    /**
     * Paragraphs of a text (separated by an empty line), escaped, the simple line breaks being kept.
     * @return string[] html
     */
    public static function paragraphs(?string $text): array
    {
        $paragraphs = preg_split('/\R\s*\R/', trim((string) $text)) ?: [];
        return array_values(array_map(fn($paragraph) => nl2br(esc(trim($paragraph))), array_filter($paragraphs, fn($paragraph) => trim($paragraph) !== '')));
    }
}
