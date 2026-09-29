<?php

namespace App\Helpers;

/**
 * Navigation of the site, defined once and used by the desktop menu, the mobile menu and the footer.
 */
class NavigationHelper
{
    /**
     * Menus of the site: label, footer title, links (href, label, icon of components/icon) and unit (its sections are listed, see menu()).
     */
    public const MENU = [
        'guide' => [
            'label' => 'Unité guide',
            'unit' => 'guide',
            'footer' => 'Guide',
            'links' => [
                ['href' => '/guide', 'label' => 'Présentation', 'icon' => 'presentation'],
                ['href' => '/guide/staff', 'label' => 'Staff d\'unité', 'icon' => 'users'],
                ['href' => '/guide/document', 'label' => 'Documents', 'icon' => 'document'],
                ['href' => '/galerie/guide', 'label' => 'Galerie photos', 'icon' => 'photo'],
            ],
        ],
        'scout' => [
            'label' => 'Unité scoute',
            'unit' => 'scout',
            'footer' => 'Scouts',
            'links' => [
                ['href' => '/scout', 'label' => 'Présentation', 'icon' => 'presentation'],
                ['href' => '/scout/staff', 'label' => 'Staff d\'unité', 'icon' => 'users'],
                ['href' => '/scout/document', 'label' => 'Documents', 'icon' => 'document'],
                ['href' => '/galerie/scout', 'label' => 'Galerie photos', 'icon' => 'photo'],
            ],
        ],
        'asbl' => [
            'label' => 'ASBL',
            'footer' => 'ASBL',
            'links' => [
                ['href' => '/asbl', 'label' => 'Présentation', 'icon' => 'presentation'],
                ['href' => '/asbl/evenements', 'label' => 'Événements', 'icon' => 'calendar-days'],
                ['href' => '/contact', 'label' => 'Contact', 'icon' => 'phone'],
            ],
        ],
        'en_pratique' => [
            'label' => 'En pratique',
            'footer' => 'En pratique',
            'links' => [
                ['href' => '/en-pratique/inscription', 'label' => 'Inscription', 'icon' => 'login'],
                ['href' => '/en-pratique/cotisation', 'label' => 'Cotisation', 'icon' => 'banknotes'],
                ['href' => '/en-pratique/agenda', 'label' => 'Agenda', 'icon' => 'calendar-days'],
            ],
        ],
    ];

    /**
     * Menus with the sections of their unit (UnitHelper).
     */
    public static function menu(): array
    {
        $menu = self::MENU;
        foreach ($menu as $key => $item) {
            if (isset($item['unit']))
                $menu[$key]['sections'] = UnitHelper::sections($item['unit']);
        }
        return $menu;
    }

    /**
     * Columns of the footer (the scouts first, like before).
     */
    public const FOOTER = ['scout', 'guide', 'asbl', 'en_pratique'];

    /**
     * Administration pages, with the roles allowed to see them (same roles as the filters of app/Config/Routes.php).
     */
    public const ADMIN_LINKS = [
        ['href' => '/admin/document', 'label' => 'Documents', 'roles' => ['super_admin', 'guide_admin', 'scout_admin']],
        ['href' => '/admin/agenda', 'label' => 'Agenda', 'roles' => ['admin', 'super_admin', 'guide_admin', 'scout_admin', 'asbl_admin']],
        ['href' => '/admin/galerie', 'label' => 'Galerie', 'roles' => ['super_admin', 'guide_admin', 'scout_admin']],
        ['href' => '/admin/inscriptions', 'label' => 'Inscriptions', 'roles' => ['super_admin', 'guide_admin', 'scout_admin']],
        ['href' => '/admin/messages', 'label' => 'Messages', 'roles' => ['super_admin', 'asbl_admin']],
        ['href' => '/admin/cotisations', 'label' => 'Cotisations', 'roles' => ['super_admin', 'asbl_admin']],
        ['href' => '/admin/responsables', 'label' => 'Responsables', 'roles' => ['super_admin']],
        ['href' => '/admin/utilisateurs', 'label' => 'Utilisateurs', 'roles' => ['super_admin']],
        ['href' => '/admin/logs', 'label' => 'Journal', 'roles' => ['super_admin']],
    ];

    /**
     * Administration links allowed by the given roles.
     */
    public static function adminLinks(array $roles): array
    {
        return array_values(array_filter(self::ADMIN_LINKS, fn($link) => array_intersect($roles, $link['roles'])));
    }
}
