<?= $this->extend('pages/default') ?>

<?= $this->section('page_title') ?>
   Guides - Présentation des sections
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
   use App\Helpers\UnitHelper;

   // Texts of the sections (title and paragraphs), in the order of UnitHelper
   $texts = [
      'nutons' => [
         'title' => 'Nutons ami de tous',
         'paragraphs' => [
            'L’aventure des nutons commence à partir de 5 ans. La Chaumière a pour objectif de rencontrer et s’ouvrir aux autres, de reconnaître et d’exprimer ses émotions, de favoriser la découverte et l’épanouissement mais aussi de faire ses premiers pas vers l’autonomie.',
            'Pour se faire, les chefs prennent soin des animés, de leur sécurité, de leur bien-être.',
            'Chaque semaine, ils sont invités à découvrir de nouveaux univers pour partager ensemble de beaux moments d’amusement, pour apprendre à se connaître mais aussi à connaître les autres.',
         ],
      ],
      'lutins' => [
         'title' => 'Lutins de notre mieux',
         'paragraphs' => [
            'Le passage de la Chaumière à la Ronde se fait à 7 ans. Les objectifs des Nutons se poursuivent aux Lutins et d’autres viennent s’y ajouter. Aux Lutins, les jeunes découvrent la vie en plus petits groupes, en sizaines.',
            'Elles sont invitées à prendre davantage de responsabilité au fil de l’année et à s’autonomiser de manière ajustée. Le grand passage des Lutins est l’engagement de la promesse. Pour se faire, les jeunes sont accompagnées durant l’année et le camp.',
            'La promesse Lutin se base sur les Règles d’Or, c’est une manière de s’engager dans la Ronde, de s’impliquer dans la vie des Guides. En retour, la Ronde te fait confiance et t’accueille tel que tu es. Le jeu, le plaisir, les animations, les rencontres restent au centre du mouvement.',
         ],
      ],
      'aventures' => [
         'title' => 'Aventures toujours prêt',
         'paragraphs' => [
            'Bienvenue chez les aventures à partir de 11ans. Le groupe s’appelle la Compagnie. Les valeurs et les expériences continuent de s’ajouter. Maintenant, le groupe est divisé en Patrouille. Au sein de la Patrouille, c’est un véritable petit système qui s’organise et qui s’ajuste entre responsabilité, transmettre et apprendre des compétences techniques (badges), mener des projets, s’investir dans des « bonnes actions », etc. En Patrouille et dans la Compagnie, la guide continue d’apprendre à se découvrir mais aussi à vivre dans la nature, en plein air en respectant son environnement.',
            'Lors des années dans la Compagnie, la Guide est invitée à découvrir la Loi Guide et à s’engager dans une deuxième promesse.',
            'Tous ces changements et étapes sont accompagnés les chefs. Le plaisir reste au centre. Et le respect du rythme de chacune est fondamental.',
         ],
      ],
      'horizons' => [
         'title' => 'Horizons entreprendre',
         'paragraphs' => [
            'A 15 ans, les Horizons prennent le relais. Les jeunes intègrent la Chaine. C’est un espace idéal pour apprendre, développer ses compétences, mettre tes compétences au service des autres et assumer ses responsabilités. Chez les Horizons, les jeunes co-construisent un projet commun. L’implication et la présence de chacune est donc essentiel. C’est ensemble que le projet peur aboutir. <br>Le rôle des chefs évolue, ils guident et soutiennent les animés. Les véritables actrices sont les jeunes. Elles sont leur propre moteur',
            'Leur année est rythmée de différents projets avec plusieurs objectifs : s’impliquer dans des Entreprises sociales, culturelles, d’animation. Etc. L’objectif final étant d’arriver au camp.',
            'Durant les années dans la Chaine, la formation à l’animation prend plus de place. Effectivement, c’est la dernière section avant de devenir chef. Une attention particulière et l’implication des Horizons dans les sections commencent afin de les préparer à la magnifique aventure d’être chef et d’accompagner des dizaines de jeunes.',
         ],
      ],
   ];
?>

<div class="mx-auto max-w-7xl">
   <div id="presentation" class="px-6 py-12 lg:px-8 xl:py-20">
      <?= component('page_intro', ['title' => 'Présentation des sections', 'columns' => [
      ['Les Guides sont un mouvement de jeunesse reconnu par la Fédération Wallonie-Bruxelles. Le mouvement est ouvert à tous à partir de 5 ans. La mission est de soutenir les jeunes dans leur développement global et ce par le jeu, l’amusement, le plaisir partagé mais aussi par la découverte et la transmission de valeurs.',
       'Les principales défendues sont le partage, le respect, la responsabilité, l’ouverture et la confiance. Mais on y retrouve aussi l’amitié, la simplicité, l’honnêteté, la loyauté, la créativité, la coopération, le volontariat, etc.'],
      ['Ces valeurs sont les guidelines de l’animation hebdomadaire et au fil de l’année. L’animation se fait par des jeunes bénévoles qui ont à cœur d’accompagner les jeunes de la manière la plus ajustée possible.',
       'Toute la pédagogie dont le projet pédagogique, les fondements du mouvement mais aussi son histoire sont à lire sur le site : <a class="link" href="https://www.guides.be/">www.guides.be</a>'],
   ]]) ?>
   </div>

   <?php foreach (UnitHelper::sections('guide') as $index => $section): ?>
      <?= component('section_presentation', ['section' => $section, 'index' => $index, 'subtitle' => $section['ages']] + $texts[$section['slug']]) ?>
   <?php endforeach; ?>

   <?= component('unit_links', ['unit' => 'guide']) ?>
</div>
<?= $this->endSection() ?>
