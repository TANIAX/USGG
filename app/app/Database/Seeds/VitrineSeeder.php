<?php

namespace App\Database\Seeds;

use App\Helpers\DocumentHelper;
use App\Helpers\FileHelper;
use App\Helpers\GalleryHelper;
use App\Helpers\ImageHelper;
use App\Repositories\EventRepository;
use CodeIgniter\Database\Seeder;

/**
 * Example site, well filled: section leaders with photos, albums of photos, events of the agenda,
 * documents, registration requests, contact messages, newsletter subscribers and history.
 * The pictures are drawn by VitrineImages (no download). The dates follow today's date.
 *
 * php spark db:seed VitrineSeeder   (Docker: docker compose --profile vitrine up --build)
 * Does nothing if the database already has albums (start again from zero: docker compose down -v).
 */
class VitrineSeeder extends Seeder
{
    /**
     * Leaders by section: [firstname, name, totem, function]
     */
    private const LEADERS = [
        'unite' => [['Claire', 'Dubois', 'Castor Tenace', 'Chef d\'unité'], ['Thomas', 'Lambert', 'Loup Serein', 'Chef d\'unité'], ['Sophie', 'Maes', 'Chouette Attentive', 'Trésorier']],
        'nutons' => [['Julie', 'Peeters', 'Écureuil Malicieux', 'Animateur responsable'], ['Lucas', 'Martin', 'Hérisson', 'Animateur'], ['Emma', 'Leroy', 'Coccinelle', 'Animateur']],
        'lutins' => [['Manon', 'Renard', 'Mésange Joyeuse', 'Animateur responsable'], ['Chloé', 'Dupont', 'Loutre', 'Animateur'], ['Sarah', 'Jacobs', 'Libellule', 'Animateur']],
        'aventures' => [['Camille', 'Hermans', 'Lynx Rusé', 'Animateur responsable'], ['Léa', 'Simon', 'Gazelle', 'Animateur'], ['Zoé', 'Laurent', 'Hirondelle', 'Animateur']],
        'horizons' => [['Pauline', 'Wouters', 'Aigle Fidèle', 'Animateur responsable'], ['Alice', 'Goossens', 'Panthère', 'Animateur']],
        'baladins' => [['Nicolas', 'Claes', 'Koala Patient', 'Animateur responsable'], ['Élise', 'Denis', 'Marmotte', 'Animateur'], ['Hugo', 'Michel', 'Lapin', 'Animateur']],
        'louveteaux' => [['Antoine', 'Mertens', 'Akela', 'Animateur responsable'], ['Louis', 'Lemaire', 'Baloo', 'Animateur'], ['Maxime', 'Willems', 'Bagheera', 'Animateur'], ['Inès', 'Dumont', 'Kaa', 'Animateur']],
        'eclaireurs' => [['Julien', 'Janssens', 'Faucon Vif', 'Animateur responsable'], ['Arthur', 'Collin', 'Blaireau', 'Animateur'], ['Victor', 'Gilles', 'Ours Brun', 'Animateur']],
        'pionniers' => [['Quentin', 'Lejeune', 'Grand Cerf', 'Animateur responsable'], ['Romain', 'François', 'Bison', 'Animateur']],
    ];

    /**
     * Albums: [branch, title, description, days ago, scenes of the photos]
     */
    private const ALBUMS = [
        ['GUIDE', 'Camp des Lutins à Bouillon', 'Dix jours au bord de la Semois : constructions, grands jeux et veillées.', 60, ['camp', 'group', 'forest', 'evening', 'lake', 'camp', 'group', 'forest', 'camp', 'evening', 'group', 'lake']],
        ['GUIDE', 'Hike des Horizons en Ardenne', 'Trois jours de marche entre Laroche et Houffalize, sac au dos.', 95, ['forest', 'group', 'lake', 'forest', 'camp', 'evening', 'forest', 'group']],
        ['GUIDE', 'Journée d\'unité guide', 'Toutes les sections réunies pour la rentrée : jeux, montées et goûter.', 20, ['group', 'group', 'forest', 'camp', 'group', 'lake', 'group']],
        ['GUIDE', 'Week-end des Aventures', 'Un week-end en tente dans les bois de Villers-la-Ville.', 140, ['camp', 'forest', 'evening', 'group', 'camp', 'lake', 'evening', 'group', 'forest']],
        ['GUIDE', 'Fête des Nutons', 'Spectacle, bricolages et chasse au trésor avec les familles.', 200, ['group', 'lake', 'forest', 'group', 'camp', 'group']],
        ['SCOUTE', 'Camp des Louveteaux à Durbuy', 'Le Livre de la Jungle grandeur nature pendant dix jours.', 58, ['camp', 'group', 'forest', 'evening', 'camp', 'lake', 'group', 'evening', 'forest', 'camp', 'group']],
        ['SCOUTE', 'Grand camp des Pionniers', 'Construction d\'un pont de singe et service dans un village partenaire.', 75, ['camp', 'group', 'lake', 'camp', 'forest', 'evening', 'group', 'camp']],
        ['SCOUTE', 'Hike des Éclaireurs', 'Orientation, cartes et bivouac sous les étoiles.', 110, ['forest', 'lake', 'group', 'evening', 'forest', 'camp', 'forest']],
        ['SCOUTE', 'Journée d\'unité scoute', 'Grand jeu dans le parc et remise des foulards.', 21, ['group', 'forest', 'group', 'group', 'camp', 'lake']],
        ['SCOUTE', 'Week-end Baladins', 'Premier week-end loin de la maison pour les plus jeunes.', 170, ['group', 'camp', 'forest', 'evening', 'group']],
    ];

    /**
     * Events: [title, description, location, day offset, start hour, duration (hours, 0 = all the day, >24 = several days), sections, image scene or null, registration]
     */
    private const EVENTS = [
        ['Réunion de rentrée', "Première réunion de l'année pour toutes les sections.\nRendez-vous au local en uniforme, avec le goûter.", 'Local de Gosselies', 5, 14, 3, ['unite'], 'group', false],
        ['Balade Nutons & Lutins', 'Balade dans les bois avec jeux d\'observation. Prévoir des bottes.', 'Bois de Lodelinsart', 12, 10, 3, ['nutons', 'lutins'], 'forest', false],
        ['Réunion Louveteaux', 'Grand jeu de la jungle au parc.', 'Parc de Gosselies', 12, 14, 3, ['louveteaux'], null, false],
        ['Week-end des Aventures', 'Week-end sous tente : départ le vendredi soir, retour le dimanche à 16 h.', 'Villers-la-Ville', 19, 18, 46, ['aventures'], 'camp', true],
        ['Souper des Pionniers', 'Souper spaghetti au profit du camp des Pionniers. Réservation conseillée.', 'Salle Saint-Joseph', 26, 0, 0, ['pionniers'], 'evening', true],
        ['Hike des Éclaireurs', 'Trois jours de marche et de bivouac. Liste du matériel envoyée par e-mail.', 'Ardenne', 33, 9, 56, ['eclaireurs'], 'lake', false],
        ['Week-end d\'unité', 'Toute l\'unité se retrouve pour un week-end de jeux et de veillées.', 'Centre scout de Wépion', 40, 18, 46, ['unite'], 'evening', true],
        ['Réunion Baladins', 'Bricolages et jeux au local.', 'Local de Gosselies', 47, 14, 3, ['baladins'], null, false],
        ['Opération Calendriers', 'Vente de calendriers en porte-à-porte au profit des camps.', 'Gosselies', 54, 0, 0, ['unite'], null, false],
        ['Fête de Noël de l\'unité', 'Chants, spectacle des sections et vin chaud pour les parents.', 'Local de Gosselies', 75, 17, 4, ['unite'], 'evening', true],
        ['Réunion Horizons', 'Préparation du projet de l\'année.', 'Local de Gosselies', 61, 14, 3, ['horizons'], null, false],
        ['Journée d\'unité', 'Grand jeu dans le parc et remise des foulards.', 'Parc de Gosselies', -21, 10, 7, ['unite'], 'group', false],
        ['Soirée des parents', 'Présentation de l\'année et des staffs.', 'Local de Gosselies', -14, 19, 2, ['unite'], null, false],
        ['Réunion Lutins', 'Jeux de piste et cabanes.', 'Bois de Lodelinsart', -7, 14, 3, ['lutins'], 'forest', false],
        ['Camp des Louveteaux', 'Dix jours à Durbuy.', 'Durbuy', -60, 10, 228, ['louveteaux'], 'camp', false],
    ];

    private const DOCUMENTS = [
        ['GUIDE', 'Fiche santé', ['Fiche santé individuelle', 'À compléter pour chaque membre et à remettre au staff avant le premier week-end.', 'Allergies, traitements, régime alimentaire, personnes de contact.']],
        ['GUIDE', 'Règlement de l\'unité guide', ['Règlement de l\'unité guide', 'Horaires des réunions, uniforme, cotisation et communication avec les familles.']],
        ['GUIDE', 'Liste du matériel de camp', ['Liste du matériel de camp', 'Sac de couchage, matelas, lampe de poche, gourde, vêtements de pluie, uniforme complet.']],
        ['GUIDE', 'Autorisation parentale', ['Autorisation parentale', 'Je soussigné(e) autorise mon enfant à participer aux activités de l\'unité.']],
        ['SCOUTE', 'Fiche santé', ['Fiche santé individuelle', 'À compléter pour chaque membre et à remettre au staff avant le premier week-end.']],
        ['SCOUTE', 'Règlement de l\'unité scoute', ['Règlement de l\'unité scoute', 'Horaires des réunions, uniforme, cotisation et communication avec les familles.']],
        ['SCOUTE', 'Liste du matériel de hike', ['Liste du matériel de hike', 'Sac à dos, carte, boussole, couverture de survie, gourde, trousse de secours.']],
        ['SCOUTE', 'Calendrier du premier quadrimestre', ['Calendrier du premier quadrimestre', 'Réunions chaque samedi de 14 h à 17 h, sauf pendant les congés scolaires.'], false],
    ];

    private array $sections = [];
    private array $types = [];
    private int $authorId = 1;

    public function run()
    {
        // Roles, functions and test accounts first (does nothing if they exist)
        $this->call('DatabaseSeeder');

        if ($this->db->table('album')->countAllResults() > 0) {
            echo "Le site exemple est déjà rempli (albums présents) : rien n'a été ajouté.\n";
            return;
        }
        if (!function_exists('imagecreatetruecolor')) {
            echo "L'extension PHP gd est nécessaire pour dessiner les photos du site exemple.\n";
            return;
        }

        foreach ($this->db->table('section')->get()->getResultObject() as $section)
            $this->sections[$section->slug] = $section;
        $this->authorId = (int) ($this->db->table('user')->where('email', 'super_admin@gmail.com')->get()->getRow()->id ?? 1);

        $this->leaders();
        $this->albums();
        $this->events();
        $this->documents();
        $this->registrations();
        $this->messages();
        $this->newsletter();
        $this->history();

        echo "Site exemple rempli.\n";
    }

    private function leaders(): void
    {
        $count = 0;
        foreach (self::LEADERS as $slug => $leaders) {
            foreach ($leaders as $position => [$firstname, $name, $totem, $function]) {
                $picture = ImageHelper::randomName() . '.jpg';
                ImageHelper::saveJpeg(VitrineImages::portrait(crc32($totem), $this->sections[$slug]->color, 400), ROOTPATH . 'public/' . FileHelper::PROFIL_PICTURE_DIRECTORY . $picture);

                $email = $this->slug($firstname . '.' . $name) . '@exemple.be';
                $this->db->table('user')->insert([
                    'firstname' => $firstname, 'name' => $name, 'totem' => $totem, 'email' => $email,
                    'password' => password_hash($email, PASSWORD_DEFAULT), 'phone' => '+32 47' . sprintf('%d %02d %02d %02d', $count % 10, 10 + $count, 20 + $count, 30 + $count),
                    'picture' => $picture, 'user_type_id' => $this->type($function), 'created_at' => date('Y-m-d H:i:s'),
                ]);
                $userId = (int) $this->db->insertID();
                $this->db->table('user_role')->insert(['user_id' => $userId, 'role_id' => $this->roleId('user')]);
                $this->db->table('section_leader')->insert([
                    'user_id' => $userId, 'section_id' => $this->sections[$slug]->id, 'user_type_id' => $this->type($function),
                    'position' => $position, 'created_at' => date('Y-m-d H:i:s'),
                ]);
                $count++;
            }
        }
        echo "  $count responsables de section (avec photo)\n";
    }

    private function albums(): void
    {
        $albums = $photos = 0;
        $photoRepository = service('repository', 'Photo');
        foreach (self::ALBUMS as $index => [$branch, $title, $description, $daysAgo, $scenes]) {
            $albumId = service('repository', 'Album')->create([
                'title' => $title, 'description' => $description, 'branch' => $branch,
                'album_date' => date('Y-m-d', strtotime("-$daysAgo days")), 'user_id' => $this->authorId,
            ]);
            $colors = array_values(array_map(fn($section) => $section->color, array_filter($this->sections, fn($section) => $section->branch === $branch)));
            foreach ($scenes as $number => $scene) {
                $portrait = $number % 5 === 3;
                $seed = $index * 100 + $number;
                $source = VitrineImages::toTempJpeg(VitrineImages::scene($scene, $seed, $portrait ? 1067 : 1600, $portrait ? 1400 : 1067, $colors[$seed % count($colors)]));
                $stored = GalleryHelper::storePhoto($source, $albumId);
                unlink($source);
                // A few photos reserved to the members (visible once connected)
                $photoRepository->add($stored + ['album_id' => $albumId, 'is_public' => $number % 4 !== 2, 'user_id' => $this->authorId]);
                $photos++;
            }
            $albums++;
        }
        echo "  $albums albums, $photos photos\n";
    }

    private function events(): void
    {
        $events = service('repository', 'Event');
        foreach (self::EVENTS as $index => [$title, $description, $location, $day, $hour, $duration, $slugs, $scene, $registration]) {
            $start = strtotime(date('Y-m-d', strtotime(($day >= 0 ? '+' : '') . $day . ' days')) . sprintf(' %02d:00:00', $hour));
            $allDay = $duration === 0;
            $image = null;
            if ($scene) {
                $image = ImageHelper::randomName();
                $picture = VitrineImages::scene($scene, 500 + $index, 1600, 900, $this->sections[$slugs[0]]->color);
                ImageHelper::saveJpeg(ImageHelper::fit($picture, 1600), EventRepository::getImagePath($image));
                ImageHelper::saveJpeg(ImageHelper::fit($picture, 800), EventRepository::getImagePath($image, true));
            }
            $events->create([
                'title' => $title, 'description' => $description, 'location' => $location,
                'start_at' => date('Y-m-d H:i:s', $allDay ? strtotime(date('Y-m-d', $start)) : $start),
                'end_at' => date('Y-m-d H:i:s', $allDay ? strtotime(date('Y-m-d', $start) . ' 23:59:00') : $start + $duration * 3600),
                'all_day' => $allDay, 'registration_url' => $registration ? 'https://forms.example.org/usgg-' . ($index + 1) : null,
                'image' => $image, 'user_id' => $this->authorId,
            ], array_map(fn($slug) => (int) $this->sections[$slug]->id, $slugs));
        }
        echo '  ' . count(self::EVENTS) . " événements (passés et à venir)\n";
    }

    private function documents(): void
    {
        if (!is_dir(DocumentHelper::privateDirectory()))
            mkdir(DocumentHelper::privateDirectory(), 0775, true);

        foreach (self::DOCUMENTS as $document) {
            [$type, $name, $lines] = $document;
            $pdf = $this->pdf($lines);
            $storedName = DocumentHelper::newStoredName('pdf');
            file_put_contents(DocumentHelper::privateDirectory() . $storedName, $pdf);
            service('repository', 'File')->create([
                'name' => $name . '.pdf', 'path' => strtolower($type), 'file_type' => $type, 'is_active' => $document[3] ?? true,
                'stored_name' => $storedName, 'mime_type' => 'application/pdf', 'size' => strlen($pdf),
            ]);
        }
        echo '  ' . count(self::DOCUMENTS) . " documents PDF\n";
    }

    private function registrations(): void
    {
        $requests = [
            ['Léon', 'Dupuis', 'lutins', 'sibling', 'new', 'Sa grande sœur est chez les Aventures.', 2],
            ['Nina', 'Lefèvre', 'nutons', 'none', 'new', null, 4],
            ['Adam', 'Vermeulen', 'louveteaux', 'former_member', 'new', 'Son papa était Éclaireur à Gosselies.', 1],
            ['Jade', 'Bernard', 'aventures', 'none', 'contacted', 'Préfère les réunions du samedi.', 9],
            ['Tom', 'Delvaux', 'baladins', 'sibling', 'contacted', null, 12],
            ['Lina', 'Masson', 'lutins', 'none', 'waiting', null, 20],
            ['Noah', 'Hubert', 'eclaireurs', 'none', 'trial', 'Vient d\'une autre unité (déménagement).', 15],
            ['Mila', 'Charlier', 'horizons', 'former_member', 'registered', null, 30],
            ['Sacha', 'Leclercq', 'pionniers', 'none', 'registered', null, 35],
            ['Rose', 'Thiry', 'nutons', 'none', 'refused', null, 40],
        ];
        $ages = ['nutons' => 7, 'lutins' => 9, 'aventures' => 12, 'horizons' => 15, 'baladins' => 7, 'louveteaux' => 9, 'eclaireurs' => 13, 'pionniers' => 16];
        $notes = ['contacted' => 'Appel le mardi, envoi du dossier.', 'waiting' => 'Section complète, première sur la liste.', 'trial' => 'Réunion d\'essai samedi prochain.', 'registered' => 'Cotisation payée.', 'refused' => 'Famille a choisi une unité plus proche.'];
        foreach ($requests as $index => [$firstname, $name, $slug, $relation, $status, $remark, $daysAgo]) {
            $this->db->table('registration')->insert([
                'firstname' => $firstname, 'name' => $name, 'birthdate' => date('Y-m-d', strtotime('-' . $ages[$slug] . ' years -' . ($index * 37) . ' days')),
                'section_id' => $this->sections[$slug]->id, 'relation' => $relation,
                'street' => ['Rue de la Station', 'Chaussée de Bruxelles', 'Rue du Calvaire', 'Place Albert Ier'][$index % 4], 'number' => (string) (3 + $index * 7),
                'zip_code' => '6041', 'city' => 'Gosselies', 'parent_name' => ['Marie', 'Pierre', 'Nathalie', 'David', 'Isabelle'][$index % 5] . ' ' . $name,
                'parent_email' => $this->slug('parent.' . $name) . '@exemple.be', 'parent_phone' => '+32 49' . (1 + $index) . ' 12 34 ' . sprintf('%02d', 10 + $index),
                'remark' => $remark, 'status' => $status, 'note' => $notes[$status] ?? null,
                'updated_by' => $status === 'new' ? null : $this->authorId, 'updated_at' => $status === 'new' ? null : date('Y-m-d H:i:s', strtotime('-' . max(0, $daysAgo - 2) . ' days')),
                'created_at' => date('Y-m-d H:i:s', strtotime("-$daysAgo days -" . ($index * 3) . ' hours')),
            ]);
        }
        echo '  ' . count($requests) . " demandes d'inscription (tous les statuts)\n";
    }

    private function messages(): void
    {
        $messages = [
            ['Anne Moreau', 'anne.moreau@exemple.be', '+32 478 11 22 33', "Bonjour,\nMon fils a 8 ans : reste-t-il de la place chez les Louveteaux cette année ?\nMerci !", false, false, 1],
            ['Marc Lenoir', 'marc.lenoir@exemple.be', null, 'Est-il possible de louer le local pour un anniversaire un dimanche ?', false, false, 3],
            ['Service jeunesse de Charleroi', 'jeunesse@exemple.be', '+32 71 00 00 00', 'Nous organisons une journée des mouvements de jeunesse en mai. Souhaitez-vous tenir un stand ?', true, false, 6],
            ['Fabienne Rousseau', 'f.rousseau@exemple.be', null, 'Merci pour le camp, notre fille est revenue enchantée !', true, true, 12],
            ['Olivier Petit', 'olivier.petit@exemple.be', '+32 486 55 44 33', 'Pouvez-vous me renvoyer le numéro de compte pour la cotisation ?', true, true, 20],
        ];
        foreach ($messages as [$name, $email, $phone, $message, $read, $handled, $daysAgo]) {
            $this->db->table('contact_message')->insert([
                'name' => $name, 'email' => $email, 'phone' => $phone, 'message' => $message,
                'is_read' => $read, 'is_handled' => $handled, 'created_at' => date('Y-m-d H:i:s', strtotime("-$daysAgo days")),
            ]);
        }
        echo '  ' . count($messages) . " messages de contact\n";
    }

    private function newsletter(): void
    {
        $count = 0;
        foreach (['famille.dubois', 'papa.louveteau', 'maman.lutin', 'ancien.pionnier', 'mamy.claes', 'famille.renard', 'j.martin', 'parents.simon', 'c.peeters', 'amis.unite', 'n.hermans', 'famille.leroy'] as $index => $name) {
            $status = $index < 9 ? 'active' : ($index < 11 ? 'pending' : 'unsubscribed');
            $this->db->table('newsletter_subscriber')->insert([
                'email' => $name . '@exemple.be', 'token' => bin2hex(random_bytes(16)),
                'confirmed_at' => $status === 'pending' ? null : date('Y-m-d H:i:s', strtotime('-' . (80 - $index * 5) . ' days')),
                'unsubscribed_at' => $status === 'unsubscribed' ? date('Y-m-d H:i:s', strtotime('-10 days')) : null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-' . (80 - $index * 5) . ' days')),
            ]);
            $count++;
        }
        foreach ([['Les activités de septembre', 6, 7, 45], ['Les activités d\'octobre', 8, 9, 15]] as [$subject, $events, $recipients, $daysAgo]) {
            $this->db->table('newsletter_sending')->insert([
                'subject' => $subject, 'event_count' => $events, 'recipient_count' => $recipients, 'failed_count' => 0,
                'sent_by' => $this->authorId, 'created_at' => date('Y-m-d H:i:s', strtotime("-$daysAgo days")),
            ]);
        }
        echo "  $count abonnés à la newsletter, 2 envois\n";
    }

    private function history(): void
    {
        $entries = [
            ['created', 'Album', 'Journée d\'unité scoute', '/admin/galerie', 20],
            ['created', 'Événement', 'Week-end d\'unité', '/admin/agenda', 15],
            ['sent', 'Newsletter', 'Les activités d\'octobre (9 abonnés)', '/admin/newsletter', 15],
            ['updated', 'Inscription', 'Mila Charlier : Inscrit', '/admin/inscriptions', 8],
            ['updated', 'Section', 'Louveteaux', '/admin/sections', 5],
            ['created', 'Document', 'Liste du matériel de hike.pdf', '/admin/document', 2],
        ];
        foreach ($entries as [$action, $subject, $label, $url, $daysAgo]) {
            $this->db->table('audit_log')->insert([
                'user_id' => $this->authorId, 'action' => $action, 'subject' => $subject, 'label' => $label, 'url' => $url,
                'created_at' => date('Y-m-d H:i:s', strtotime("-$daysAgo days")),
            ]);
        }
    }

    // ------------------------------------------------------------------ tools

    /**
     * Id of a function of the staff (created if missing).
     */
    private function type(string $name): int
    {
        if (!isset($this->types[$name])) {
            $row = $this->db->table('user_type')->where('name', $name)->get()->getRow();
            if (!$row) {
                $this->db->table('user_type')->insert(['name' => $name]);
                $this->types[$name] = (int) $this->db->insertID();
            } else {
                $this->types[$name] = (int) $row->id;
            }
        }
        return $this->types[$name];
    }

    private function roleId(string $name): int
    {
        return (int) $this->db->table('role')->where('name', $name)->get()->getRow()->id;
    }

    private function slug(string $text): string
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        return strtolower(preg_replace('/[^A-Za-z0-9.]+/', '', $text));
    }

    /**
     * Small PDF (one A4 page): a title then paragraphs, in Helvetica.
     */
    private function pdf(array $lines): string
    {
        $text = "BT /F1 20 Tf 60 770 Td (" . $this->pdfText(array_shift($lines)) . ") Tj ET\n";
        $text .= "BT /F1 11 Tf 60 740 Td (Guides et Scouts de Gosselies - document d'exemple) Tj ET\n";
        $y = 700;
        foreach ($lines as $line) {
            foreach (explode("\n", wordwrap($line, 90)) as $part) {
                $text .= "BT /F1 12 Tf 60 $y Td (" . $this->pdfText($part) . ") Tj ET\n";
                $y -= 18;
            }
            $y -= 10;
        }
        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 5 0 R >> >> /Contents 4 0 R >>',
            "<< /Length " . strlen($text) . " >>\nstream\n" . $text . "endstream",
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>',
        ];
        $pdf = "%PDF-1.4\n";
        $offsets = [];
        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1) . " 0 obj\n" . $object . "\nendobj\n";
        }
        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";
        foreach ($offsets as $offset)
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        return $pdf . "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n$xref\n%%EOF\n";
    }

    private function pdfText(string $text): string
    {
        $text = iconv('UTF-8', 'Windows-1252//TRANSLIT', $text);
        return strtr($text, ['\\' => '\\\\', '(' => '\\(', ')' => '\\)']);
    }
}
