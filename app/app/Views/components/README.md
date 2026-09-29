# Composants d'interface

Morceaux de vue réutilisables, rendus par le serveur (pas de JavaScript en plus). Chaque fichier de ce dossier est un
composant ; ses paramètres (« props ») sont décrits en tête du fichier.

```php
<?= component('button', ['label' => 'Enregistrer', 'type' => 'submit']) ?>
```

Composant avec un contenu (« slot ») :

```php
<?php component_open('page_header', ['title' => 'Documents']) ?>
   <?= component('button', ['label' => 'Ajouter', 'href' => '/admin/document/create']) ?>
<?= component_close() ?>
```

Règles communes :

- un composant ne voit que ses props (les variables de la page ne sont pas visibles) ;
- `class` ajoute des classes à l'élément principal, `attrs` ajoute des attributs, y compris Alpine
  (`'attrs' => ['x-show' => 'open', '@click' => 'close()']`) ;
- les textes (`label`, `title`...) sont échappés ; le `slot` et les props terminées par `_html` sont du HTML ;
- les props terminées par `_alpine` sont des expressions JavaScript évaluées par Alpine (listes affichées avec `x-for`) ;
- les classes Tailwind sont écrites en entier dans les composants (jamais construites par morceaux) : après une
  modification, reconstruire `public/assets/css/output.css`.

Le code est dans `app/Helpers/component_helper.php` (`component()`, `component_open()`, `component_close()`,
`attrs()`, `classes()`, `js_data()`).

## Liste

| Composant | Rôle |
| --- | --- |
| `icon` | Icône (Heroicons) par son nom : `component('icon', ['name' => 'calendar', 'class' => 'h-5 w-5'])` |
| `button` | Bouton ou lien-bouton : variantes `primary`, `secondary`, `danger`, `soft` ; tailles `sm`, `md`, `lg` |
| `icon_button` | Bouton ne montrant qu'une icône (avec un texte pour les lecteurs d'écran) |
| `badge` | Petite étiquette colorée (rôle, « Terminé », « Membres »...) |
| `section_tag` | Étiquette d'une section (point de sa couleur + nom), côté serveur ou Alpine |
| `chip` | Pastille de filtre (bouton ou lien), sélection côté serveur ou Alpine |
| `alert` | Message d'erreur (liste) ou de succès |
| `flash` | Messages de l'action précédente (flashdata `errors` / `success`) |
| `notice` | Encadré d'information (indigo) ou de liste vide (gris) |
| `input` | Champ seul : texte, e-mail, date, liste déroulante, zone de texte... |
| `field` | Champ complet : libellé, champ, aide |
| `checkbox` | Case à cocher : en ligne, en carte (`card`) ou en encadré gris (`panel`) |
| `file_button` | Bouton de choix de fichier |
| `search` | Champ de recherche d'une liste filtrée par Alpine |
| `form_actions` | Boutons « Annuler » / « Enregistrer » en bas d'un formulaire |
| `page_header` | Titre d'une page d'administration (lien retour, sous-titre, actions) |
| `empty_state` | Message d'une liste vide (icône, titre) |
| `selection_bar` | Barre d'actions sur les éléments sélectionnés (documents, photos) |
| `cover_carousel` | Carrousel automatique de la couverture d'un album |
| `detail` | Ligne d'information avec icône (date, lieu) |
| `auth_card` | Carte centrée des pages de compte (mot de passe oublié...) |
| `page_intro` | Titre et introduction sur deux colonnes |
| `section_presentation` | Présentation d'une section (logo alterné gauche / droite, texte) |
| `unit_links` | Liens en bas de la présentation d'une unité |
| `section_tile` | Tuile d'une section sur la page d'accueil |
| `leader` | Responsable de section (carte ou ligne) |
| `faq_item` | Question de la FAQ |
| `testimonial` | Témoignage |
| `step` | Étape d'une frise verticale (inscription) |

Les données partagées sont définies une seule fois :

- `App\Helpers\UnitHelper` : les unités et leurs sections (nom, âges, logo, ancre) ;
- `App\Helpers\NavigationHelper` : menus (ordinateur, mobile, pied de page) et liens d'administration par rôle.
