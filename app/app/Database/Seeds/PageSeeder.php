<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Pages written by the super admins (admin/pages): charter of the unit and privacy policy.
 * Default texts, inserted by the migration AddPages and by this seeder (only the missing pages: the texts
 * changed in the administration are kept). Format: see App\Libraries\PageText.
 *
 * php spark db:seed PageSeeder
 */
class PageSeeder extends Seeder
{
    public const PAGES = [
        'charte' => [
            'title' => 'Charte de fonctionnement',
            'content' => <<<'TEXT'
Cette charte rassemble les règles de vie de l'unité guide et scoute de Gosselies. Elle est un engagement réciproque entre les animés, leurs parents et les animateurs, pour que chacun vive le scoutisme dans les meilleures conditions.

## Notre projet
Le scoutisme et le guidisme sont des mouvements de jeunesse qui aident chaque enfant et chaque jeune à grandir : devenir autonome, prendre des responsabilités, vivre en équipe et s'ouvrir aux autres, dans le respect de la nature.
- Les activités sont adaptées à l'âge de chaque section.
- Le jeu, la vie en groupe et la vie dans la nature sont au cœur de nos activités.
- L'unité est ouverte à tous, sans distinction d'origine, de convictions ou de situation familiale.

## Les animateurs
Les animateurs sont des jeunes **bénévoles**, encadrés par le staff d'unité et formés par leur fédération.
- Ils préparent les réunions, les week-ends et les camps, et veillent à la sécurité des animés.
- Ils sont attentifs à chaque enfant et restent disponibles pour dialoguer avec les parents.
- Ils s'engagent à respecter la présente charte et les règles de leur fédération.

## L'engagement des animés
- Participer régulièrement aux réunions et prévenir les animateurs en cas d'absence.
- Arriver à l'heure, en uniforme complet et avec le matériel demandé.
- Respecter les autres, les animateurs, le matériel, les lieux et la nature.
- L'alcool, le tabac et toute drogue sont interdits pendant les activités.
- Le téléphone reste rangé pendant les activités, sauf demande des animateurs.

## L'engagement des parents
- Inscrire leur enfant et payer la cotisation annuelle dans les délais (voir la page [Cotisation](/en-pratique/cotisation)).
- Remettre une **fiche santé** complète et la tenir à jour (allergies, traitements, régime particulier).
- Lire les informations envoyées par les animateurs et respecter les horaires de début et de fin des activités.
- Prévenir les animateurs de toute situation qui demande une attention particulière.
- Proposer leur aide ponctuellement : transport, intendance, entretien du local, fêtes d'unité...

## Sécurité et santé
- Les membres sont assurés par la fédération pendant les activités.
- Les médicaments sont remis aux animateurs avec les instructions écrites nécessaires.
- En cas d'accident ou de maladie, les animateurs prennent les mesures nécessaires et préviennent les parents au plus vite.
- Aucun enfant ne quitte une activité avant l'heure prévue sans l'accord de ses parents.

## Respect et bien-être
Chacun a droit au respect. Les moqueries, le harcèlement, la violence et toute forme de discrimination ne sont pas acceptés. Un animé, un parent ou un animateur qui est témoin ou victime d'un comportement inapproprié peut s'adresser au staff d'unité en toute confiance, à l'adresse [{email}](mailto:{email}).

## Communication
Les informations de l'unité sont publiées sur ce site (agenda, documents, actualités) et envoyées par e-mail. Pour une question, contactez l'animateur responsable de la section ou le staff d'unité via la page [Contact](/contact).

## Photos
Des photos sont prises pendant les activités pour garder un souvenir et présenter l'unité. Certaines sont réservées aux membres connectés. Si vous ne souhaitez pas que votre enfant apparaisse sur les photos publiées, signalez-le au staff d'unité : les photos concernées seront retirées. Voir aussi la [politique de confidentialité](/confidentialite).

## Participation financière
L'argent ne doit jamais empêcher un enfant de vivre le scoutisme. Si la cotisation ou le prix d'un camp pose problème, contactez le staff d'unité : une solution sera trouvée en toute discrétion.

## En cas de difficulté
En cas de problème, le dialogue est toujours privilégié : d'abord avec les animateurs de la section, puis avec le staff d'unité. Si les règles de cette charte ne sont pas respectées de façon grave ou répétée, le staff d'unité peut, après en avoir parlé avec les parents, décider d'une mesure adaptée.
TEXT,
        ],
        'confidentialite' => [
            'title' => 'Politique de confidentialité',
            'content' => <<<'TEXT'
Cette page explique quelles données personnelles l'unité guide et scoute de Gosselies récolte via ce site, pourquoi, combien de temps elles sont gardées et quels sont vos droits, conformément au Règlement général sur la protection des données (RGPD).

## Responsable du traitement
Le responsable du traitement est l'unité guide et scoute de Gosselies, représentée par son staff d'unité. Pour toute question sur vos données : [{email}](mailto:{email}).

## Les données récoltées
### Demande d'inscription
Prénom, nom, totem et date de naissance de l'enfant, section souhaitée, adresse, nom, e-mail et téléphone du parent, lien avec l'unité et remarques éventuelles.
- **Pourquoi** : traiter la demande (places disponibles, liste d'attente, prise de contact avec la famille).
- **Base légale** : votre consentement, donné en envoyant le formulaire.
- **Durée** : les demandes sont effacées au plus tard à l'ouverture de la campagne d'inscription suivante. Les données des enfants inscrits sont ensuite gérées comme celles des membres de l'unité.

### Formulaire de contact
Nom, e-mail, téléphone (facultatif) et message.
- **Pourquoi** : vous répondre. Le message est aussi envoyé à l'adresse de l'unité.
- **Durée** : le temps nécessaire au suivi de votre demande, puis le message est supprimé.

### Newsletter
Votre adresse e-mail.
- **Pourquoi** : vous envoyer les prochaines activités de l'unité. L'inscription doit être confirmée via un lien envoyé par e-mail.
- **Désinscription** : chaque e-mail contient un lien pour vous désinscrire en un clic.
- **Durée** : jusqu'à votre désinscription. Une adresse jamais confirmée peut être supprimée à tout moment.

### Comptes des animateurs et du staff
Nom, prénom, totem, e-mail, téléphone, fonction et photo.
- **Pourquoi** : accès à l'administration du site et présentation des responsables. Seuls le totem (ou le prénom), la fonction et la photo sont affichés publiquement.
- **Durée** : tant que la personne fait partie de l'unité ; le compte est ensuite désactivé ou supprimé.

### Photos des activités
Les photos des activités sont publiées dans la galerie. Certaines ne sont visibles que par les membres connectés. Sur simple demande, une photo est retirée.

### Journaux techniques
Comme tout site, le serveur enregistre des informations techniques (adresse IP, pages demandées, navigateur, erreurs) pour assurer la sécurité et corriger les problèmes. Ces journaux sont effacés automatiquement après **90 jours**.

## Cookies
Le site utilise uniquement un cookie de session, déposé lorsque vous vous connectez à l'espace membre. Il n'y a ni cookie publicitaire, ni outil de mesure d'audience.
- Certaines photos de la page d'accueil sont chargées depuis le service Unsplash, qui reçoit à cette occasion l'adresse IP du visiteur.
- Si vous utilisez la connexion avec Google, Google reçoit la demande de connexion selon ses propres règles.

## Qui a accès à vos données ?
- Les animateurs et le staff d'unité concernés, uniquement pour les besoins de l'unité.
- La fédération dont dépend la section, pour l'affiliation et l'assurance des membres inscrits.
- Notre hébergeur, qui stocke le site et ses données pour notre compte.

Vos données ne sont jamais vendues ni utilisées à des fins commerciales.

## Sécurité
L'accès à l'administration est protégé par un mot de passe personnel et limité selon le rôle de chacun. Les documents non publics et les photos réservées aux membres ne sont pas accessibles sans connexion.

## Vos droits
Vous pouvez à tout moment demander l'accès à vos données, leur rectification, leur effacement, la limitation de leur traitement, vous opposer à leur traitement ou retirer votre consentement. Écrivez-nous à [{email}](mailto:{email}) : nous répondons dans un délai d'un mois.

Si vous estimez que vos droits ne sont pas respectés, vous pouvez introduire une réclamation auprès de l'[Autorité de protection des données](https://www.autoriteprotectiondonnees.be) (rue de la Presse 35, 1000 Bruxelles).
TEXT,
        ],
    ];

    public function run()
    {
        $added = 0;
        foreach (self::PAGES as $slug => $page) {
            if ($this->db->table('page')->where('slug', $slug)->countAllResults() > 0)
                continue;
            $this->db->table('page')->insert(['slug' => $slug, 'title' => $page['title'], 'content' => $page['content'], 'updated_at' => date('Y-m-d H:i:s')]);
            $added++;
        }
        echo $added ? "$added page(s) ajoutée(s) : charte, politique de confidentialité.\n" : "Pages déjà présentes : textes conservés.\n";
    }
}
