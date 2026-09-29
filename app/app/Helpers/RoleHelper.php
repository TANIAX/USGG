<?php

namespace App\Helpers;

/**
 * Roles that can be given to an account, with what they allow.
 * The rights themselves are defined by the filters of app/Config/Routes.php (and GalleryHelper for the guide / scout split).
 *
 * @author Guillaume Cornez
 */
class RoleHelper
{
    public const ROLES = [
        'super_admin' => [
            'label' => 'Super administrateur',
            'description' => 'Accès complet : utilisateurs et rôles, responsables de section, agenda, galerie et documents des deux unités.',
        ],
        'guide_admin' => [
            'label' => 'Administrateur guide',
            'description' => 'Agenda, galerie et documents de l\'unité guide.',
        ],
        'scout_admin' => [
            'label' => 'Administrateur scout',
            'description' => 'Agenda, galerie et documents de l\'unité scoute.',
        ],
        'asbl_admin' => [
            'label' => 'Administrateur ASBL',
            'description' => 'Agenda (événements de l\'unité, de l\'ASBL...).',
        ],
    ];

    // Every account has the base role "user": it can see the photos reserved to the members
    public const BASE_ROLE = 'user';
}
