<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Contents editable in the administration: texts of the sections, FAQ, testimonials.
 * Newsletter (subscribers, sendings) and history of the actions of the administrators.
 * The initial contents are the texts that were written in the views.
 */
class AddContentsNewsletterAudit extends Migration
{
    private const SECTIONS = [
        'nutons' => ['group_name' => 'La Chaumière', 'ages' => '5 à 7 ans', 'title' => 'Nutons ami de tous', 'description' => 'L’aventure des nutons commence à partir de 5 ans. La Chaumière a pour objectif de rencontrer et s’ouvrir aux autres, de reconnaître et d’exprimer ses émotions, de favoriser la découverte et l’épanouissement mais aussi de faire ses premiers pas vers l’autonomie.

Pour se faire, les chefs prennent soin des animés, de leur sécurité, de leur bien-être.

Chaque semaine, ils sont invités à découvrir de nouveaux univers pour partager ensemble de beaux moments d’amusement, pour apprendre à se connaître mais aussi à connaître les autres.'],
        'lutins' => ['group_name' => 'La Ronde', 'ages' => '8 à 11 ans', 'title' => 'Lutins de notre mieux', 'description' => 'Le passage de la Chaumière à la Ronde se fait à 7 ans. Les objectifs des Nutons se poursuivent aux Lutins et d’autres viennent s’y ajouter. Aux Lutins, les jeunes découvrent la vie en plus petits groupes, en sizaines.

Elles sont invitées à prendre davantage de responsabilité au fil de l’année et à s’autonomiser de manière ajustée. Le grand passage des Lutins est l’engagement de la promesse. Pour se faire, les jeunes sont accompagnées durant l’année et le camp.

La promesse Lutin se base sur les Règles d’Or, c’est une manière de s’engager dans la Ronde, de s’impliquer dans la vie des Guides. En retour, la Ronde te fait confiance et t’accueille tel que tu es. Le jeu, le plaisir, les animations, les rencontres restent au centre du mouvement.'],
        'aventures' => ['group_name' => 'La Compagnie', 'ages' => '11 à 15 ans', 'title' => 'Aventures toujours prêt', 'description' => 'Bienvenue chez les aventures à partir de 11ans. Le groupe s’appelle la Compagnie. Les valeurs et les expériences continuent de s’ajouter. Maintenant, le groupe est divisé en Patrouille. Au sein de la Patrouille, c’est un véritable petit système qui s’organise et qui s’ajuste entre responsabilité, transmettre et apprendre des compétences techniques (badges), mener des projets, s’investir dans des « bonnes actions », etc. En Patrouille et dans la Compagnie, la guide continue d’apprendre à se découvrir mais aussi à vivre dans la nature, en plein air en respectant son environnement.

Lors des années dans la Compagnie, la Guide est invitée à découvrir la Loi Guide et à s’engager dans une deuxième promesse.

Tous ces changements et étapes sont accompagnés les chefs. Le plaisir reste au centre. Et le respect du rythme de chacune est fondamental.'],
        'horizons' => ['group_name' => 'La Chaîne', 'ages' => '16 à 18 ans', 'title' => 'Horizons entreprendre', 'description' => 'A 15 ans, les Horizons prennent le relais. Les jeunes intègrent la Chaine. C’est un espace idéal pour apprendre, développer ses compétences, mettre tes compétences au service des autres et assumer ses responsabilités. Chez les Horizons, les jeunes co-construisent un projet commun. L’implication et la présence de chacune est donc essentiel. C’est ensemble que le projet peur aboutir. 
Le rôle des chefs évolue, ils guident et soutiennent les animés. Les véritables actrices sont les jeunes. Elles sont leur propre moteur

Leur année est rythmée de différents projets avec plusieurs objectifs : s’impliquer dans des Entreprises sociales, culturelles, d’animation. Etc. L’objectif final étant d’arriver au camp.

Durant les années dans la Chaine, la formation à l’animation prend plus de place. Effectivement, c’est la dernière section avant de devenir chef. Une attention particulière et l’implication des Horizons dans les sections commencent afin de les préparer à la magnifique aventure d’être chef et d’accompagner des dizaines de jeunes.'],
        'baladins' => ['ages' => '6 à 8 ans', 'title' => null, 'group_name' => 'La Ribambelle', 'description' => 'Chez les Baladins, les plus jeunes découvrent le scoutisme par le jeu, l’imaginaire et la découverte de la nature. Chaque réunion est une petite aventure, pensée pour leur âge et leur rythme.

Les animateurs veillent à ce que chacun trouve sa place dans le groupe, apprenne à vivre avec les autres et fasse ses premiers pas vers l’autonomie.

L’année se termine par un camp de quelques jours, souvent la première expérience de vie en groupe loin de la maison.'],
        'louveteaux' => ['ages' => '8 à 12 ans', 'title' => null, 'group_name' => 'La Meute', 'description' => 'Aux Louveteaux, les jeunes vivent en Meute et en sizaines, de petites équipes où chacun peut prendre une responsabilité. L’univers du Livre de la Jungle accompagne leurs aventures.

Grands jeux, bricolages, activités en forêt et veillées rythment l’année. Les louveteaux apprennent à coopérer, à se dépasser et à respecter les autres.

Au camp d’été, la Meute vit une dizaine de jours ensemble : un moment fort, attendu toute l’année.'],
        'eclaireurs' => ['ages' => '12 à 16 ans', 'title' => null, 'group_name' => 'La Troupe', 'description' => 'Chez les Éclaireurs, la Troupe s’organise en patrouilles. Les jeunes prennent de plus en plus d’initiatives : ils préparent des activités, gèrent leur matériel et apprennent à vivre en autonomie.

Hikes, constructions, techniques scoutes et vie dans la nature font partie du quotidien. C’est l’âge des défis, de la débrouille et de la solidarité au sein de la patrouille.

Le camp sous tente est le point d’orgue de l’année : on y construit son lieu de vie et on y vit l’aventure pour de vrai.'],
        'pionniers' => ['ages' => '16 à 18 ans', 'title' => null, 'group_name' => 'Le Poste', 'description' => 'Aux Pionniers, les jeunes construisent ensemble les projets qui les animent : service à la communauté, découverte d’autres cultures, activités qui ont du sens pour eux.

Le Poste apprend à s’organiser, à débattre, à financer ses projets et à les mener jusqu’au bout. Les animateurs accompagnent plus qu’ils ne dirigent.

C’est aussi l’étape qui prépare au rôle d’animateur, pour ceux qui voudront à leur tour transmettre ce qu’ils ont reçu.'],
        'unite' => ['ages' => null, 'title' => null, 'description' => null],
    ];
    private const FAQ = [
        ['Quels sont les avantages principaux de l\'inscription de mon enfant achez les guide ou les scouts?', 'Les guide ou scouts offrent de nombreux avantages tels que le développement du leadership, l\'apprentissage de compétences pratiques, la socialisation, la formation au travail d\'équipe, et la connexion avec la nature. Les activités des guides et des scouts visent à favoriser la croissance personnelle et le sens des responsabilités.'],
        ['Comment fonctionne la supervision et la sécurité lors des activités les guides et les scouts?', 'La sécurité des enfants est une priorité pour les guidew ainsi que les scouts. Les activités sont planifiées et supervisées par des adultes formés. Les camps et sorties sont organisés avec des protocoles de sécurité stricts, et les responsables sont généralement soumis à des vérifications d\'antécédents.'],
        ['Quel est l\'engagement requis de la part des parents?', 'Les parents peuvent être impliqués de différentes manières, en fonction de leurs disponibilités. Certains peuvent devenir des bénévoles actifs, tandis que d\'autres peuvent participer à des réunions ou événements ponctuels. Il est important de comprendre les attentes et de choisir un niveau d\'engagement qui convient à la famille.'],
        ['Comment les unités gèrent-elles l\'inclusion et la diversité ?', 'Les guides et les scouts s\'efforcent de promouvoir l\'inclusion et la diversité. Ils accueillent des membres de toutes origines, croyances et sexes. Les activités sont conçues pour favoriser le respect mutuel et la compréhension interculturelle. Il peut être utile de discuter avec les responsables locaux pour comprendre comment ces principes sont mis en œuvre au sein du groupe.'],
        ['Quels sont les coûts associés à l\'adhésion aux unités guide / scoute ?', 'Les coûts peuvent varier en fonction de la région et des activités spécifiques du groupe. Il est important de comprendre les frais d\'adhésion, les coûts des uniformes, des camps et des événements spéciaux. De nombreuses organisations offrent des options d\'aide financière pour assurer que la participation aux scouts soit accessible à tous.'],
    ];
    private const TESTIMONIALS = [
        ['Mon enfance chez les guides a été une expérience enrichissante. Les activités en plein air m\'ont appris la collaboration,le respect de la nature. Les souvenirs de feux de camp et d\'aventures restent des moments forts de mon enfance, façonnant des valeurs qui perdurent.', 'Brenna Goyette', 'Koala', 'https://images.unsplash.com/photo-1550525811-e5869dd03032?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=1024&h=1024&q=80'],
        ['Les guides ont été une expérience formidable. Les compétences en plein air, les amis et les valeurs positives ont marqué mon enfance de manière inoubliable.', 'Leslie Alexander', 'Guanaco', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'],
        ['Être guide a été génial. Les aventures en plein air, les amitiés durables et les valeurs enseignées ont été des éléments clés de ma jeunesse.', 'Lindsay Walton', 'Azara', 'https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'],
        ['Les années chez les scouts ont été incroyables. Les leçons de vie, les amis proches et les souvenirs resteront toujours précieux.', 'Tom Cook', 'zebre', 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'],
        ['Être scout a été une aventure enrichissante. Les activités pratiques, les amitiés solides et les valeurs positives ont laissé une empreinte durable.', 'Leonard Krasner', 'ailurus', 'https://images.unsplash.com/photo-1519345182560-3f2917c472ef?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'],
    ];

    public function up()
    {
        // Sections: presentation (title, name of the group "group_name", ages, text: paragraphs separated by an empty line)
        $this->forge->addColumn('section', [
            'title' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'group_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'ages' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'description' => ['type' => 'TEXT', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        foreach (self::SECTIONS as $slug => $texts) {
            $this->db->table('section')->where('slug', $slug)->update($texts + ['group_name' => null, 'title' => null]);
        }

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'question' => ['type' => 'VARCHAR', 'constraint' => 255],
            'answer' => ['type' => 'TEXT'],
            'position' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'is_active' => ['type' => 'BOOLEAN', 'default' => true],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('faq');
        foreach (self::FAQ as $position => [$question, $answer]) {
            $this->db->table('faq')->insert(['question' => $question, 'answer' => $answer, 'position' => $position]);
        }

        // Testimonials (the first one is highlighted on the home page). picture: url, or file of public/uploads/testimonials
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'quote' => ['type' => 'TEXT'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'totem' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'picture' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'position' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'is_active' => ['type' => 'BOOLEAN', 'default' => true],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('testimonial');
        foreach (self::TESTIMONIALS as $position => [$quote, $name, $totem, $picture]) {
            $this->db->table('testimonial')->insert(['quote' => $quote, 'name' => $name, 'totem' => $totem, 'picture' => $picture, 'position' => $position]);
        }

        // Newsletter: subscription confirmed by e-mail (double opt-in), token of the confirmation / unsubscription links
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 9, 'unsigned' => true, 'auto_increment' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 255],
            'token' => ['type' => 'VARCHAR', 'constraint' => 64],
            'confirmed_at' => ['type' => 'DATETIME', 'null' => true],
            'unsubscribed_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('token');
        $this->forge->createTable('newsletter_subscriber');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 9, 'unsigned' => true, 'auto_increment' => true],
            'subject' => ['type' => 'VARCHAR', 'constraint' => 255],
            'event_count' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'recipient_count' => ['type' => 'INT', 'constraint' => 9, 'default' => 0],
            'failed_count' => ['type' => 'INT', 'constraint' => 9, 'default' => 0],
            'sent_by' => ['type' => 'INT', 'constraint' => 5, 'null' => true],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('newsletter_sending');

        // What the administrators did (App\Helpers\AuditHelper)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 9, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 5, 'null' => true],
            'action' => ['type' => 'VARCHAR', 'constraint' => 20],
            'subject' => ['type' => 'VARCHAR', 'constraint' => 50],
            'label' => ['type' => 'VARCHAR', 'constraint' => 255],
            'url' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('created_at');
        $this->forge->createTable('audit_log');
    }

    public function down()
    {
        foreach (['faq', 'testimonial', 'newsletter_subscriber', 'newsletter_sending', 'audit_log'] as $table) {
            $this->forge->dropTable($table);
        }
        $this->forge->dropColumn('section', ['title', 'group_name', 'ages', 'description', 'updated_at']);
    }
}
