<?php

namespace App\Helpers;

/**
 * Navigation of the site, defined once and used by the desktop menu, the mobile menu and the footer.
 */
class NavigationHelper
{
    /**
     * Menus of the site: label, footer title, links (href, label, icon of lib/components/icon) and unit (its sections are listed, see menu()).
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
     * "menu": also in the menu of the account (the tools used often); all of them are on the dashboard (/admin), by group.
     */
    public const ADMIN_LINKS = [
        ['href' => '/admin/agenda', 'label' => 'Agenda', 'group' => 'Contenu du site', 'icon' => 'calendar-days', 'menu' => true, 'description' => 'Événements, actualités de l\'accueil', 'roles' => ['admin', 'super_admin', 'guide_admin', 'scout_admin', 'asbl_admin']],
        ['href' => '/admin/galerie', 'label' => 'Galerie', 'group' => 'Contenu du site', 'icon' => 'photo', 'menu' => true, 'description' => 'Albums et photos', 'roles' => ['super_admin', 'guide_admin', 'scout_admin']],
        ['href' => '/admin/document', 'label' => 'Documents', 'group' => 'Contenu du site', 'icon' => 'document', 'menu' => true, 'description' => 'Fiches et documents à télécharger', 'roles' => ['super_admin', 'guide_admin', 'scout_admin']],
        ['href' => '/admin/sections', 'label' => 'Sections', 'group' => 'Contenu du site', 'icon' => 'presentation', 'description' => 'Logos, couleurs, âges et présentations', 'roles' => ['super_admin']],
        ['href' => '/admin/contenus', 'label' => 'FAQ et témoignages', 'group' => 'Contenu du site', 'icon' => 'plus', 'description' => 'Questions fréquentes et témoignages de l\'accueil', 'roles' => ['super_admin']],
        ['href' => '/admin/inscriptions', 'label' => 'Inscriptions', 'group' => 'Familles', 'icon' => 'login', 'menu' => true, 'description' => 'Demandes d\'inscription à traiter', 'roles' => ['super_admin', 'guide_admin', 'scout_admin']],
        ['href' => '/admin/messages', 'label' => 'Messages', 'group' => 'Familles', 'icon' => 'envelope', 'menu' => true, 'description' => 'Formulaire de contact', 'roles' => ['super_admin', 'asbl_admin']],
        ['href' => '/admin/newsletter', 'label' => 'Newsletter', 'group' => 'Familles', 'icon' => 'envelope', 'description' => 'Abonnés et envoi des prochaines actualités', 'roles' => ['super_admin', 'asbl_admin']],
        ['href' => '/admin/cotisations', 'label' => 'Cotisations', 'group' => 'Familles', 'icon' => 'banknotes', 'description' => 'Montants et modalités de paiement', 'roles' => ['super_admin', 'asbl_admin']],
        ['href' => '/admin/responsables', 'label' => 'Responsables', 'group' => 'Unité', 'icon' => 'users', 'description' => 'Responsables de section présentés sur le site', 'roles' => ['super_admin']],
        ['href' => '/admin/utilisateurs', 'label' => 'Utilisateurs', 'group' => 'Unité', 'icon' => 'user', 'description' => 'Comptes et rôles d\'administration', 'roles' => ['super_admin']],
        ['href' => '/admin/fonctions', 'label' => 'Fonctions', 'group' => 'Unité', 'icon' => 'users', 'description' => 'Fonctions du staff (chef d\'unité, animateur...)', 'roles' => ['super_admin']],
        ['href' => '/admin/historique', 'label' => 'Historique', 'group' => 'Suivi', 'icon' => 'presentation', 'description' => 'Qui a modifié quoi', 'roles' => ['super_admin']],
        ['href' => '/admin/logs', 'label' => 'Journal', 'group' => 'Suivi', 'icon' => 'document', 'description' => 'Erreurs du site', 'roles' => ['super_admin']],
    ];

    /**
     * Roles having access to the dashboard (all the administrators)
     */
    public const ADMIN_ROLES = ['admin', 'super_admin', 'guide_admin', 'scout_admin', 'asbl_admin'];

    /**
     * Administration links allowed by the given roles.
     */
    public static function adminLinks(array $roles): array
    {
        return array_values(array_filter(self::ADMIN_LINKS, fn($link) => array_intersect($roles, $link['roles'])));
    }
}
