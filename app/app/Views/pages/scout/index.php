<?= $this->extend('pages/default') ?>

<?= $this->section('page_title') ?>
   Scouts - Présentation des sections
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
   use App\Helpers\UnitHelper;

   // Temporary texts, to be reviewed by the unit (subtitle and paragraphs of each section, in the order of UnitHelper)
   $texts = [
      'baladins' => [
         'subtitle' => 'La Ribambelle · 6 à 8 ans',
         'paragraphs' => [
            'Chez les Baladins, les plus jeunes découvrent le scoutisme par le jeu, l’imaginaire et la découverte de la nature. Chaque réunion est une petite aventure, pensée pour leur âge et leur rythme.',
            'Les animateurs veillent à ce que chacun trouve sa place dans le groupe, apprenne à vivre avec les autres et fasse ses premiers pas vers l’autonomie.',
            'L’année se termine par un camp de quelques jours, souvent la première expérience de vie en groupe loin de la maison.',
         ],
      ],
      'louveteaux' => [
         'subtitle' => 'La Meute · 8 à 12 ans',
         'paragraphs' => [
            'Aux Louveteaux, les jeunes vivent en Meute et en sizaines, de petites équipes où chacun peut prendre une responsabilité. L’univers du Livre de la Jungle accompagne leurs aventures.',
            'Grands jeux, bricolages, activités en forêt et veillées rythment l’année. Les louveteaux apprennent à coopérer, à se dépasser et à respecter les autres.',
            'Au camp d’été, la Meute vit une dizaine de jours ensemble : un moment fort, attendu toute l’année.',
         ],
      ],
      'eclaireurs' => [
         'subtitle' => 'La Troupe · 12 à 16 ans',
         'paragraphs' => [
            'Chez les Éclaireurs, la Troupe s’organise en patrouilles. Les jeunes prennent de plus en plus d’initiatives : ils préparent des activités, gèrent leur matériel et apprennent à vivre en autonomie.',
            'Hikes, constructions, techniques scoutes et vie dans la nature font partie du quotidien. C’est l’âge des défis, de la débrouille et de la solidarité au sein de la patrouille.',
            'Le camp sous tente est le point d’orgue de l’année : on y construit son lieu de vie et on y vit l’aventure pour de vrai.',
         ],
      ],
      'pionniers' => [
         'subtitle' => 'Le Poste · 16 à 18 ans',
         'paragraphs' => [
            'Aux Pionniers, les jeunes construisent ensemble les projets qui les animent : service à la communauté, découverte d’autres cultures, activités qui ont du sens pour eux.',
            'Le Poste apprend à s’organiser, à débattre, à financer ses projets et à les mener jusqu’au bout. Les animateurs accompagnent plus qu’ils ne dirigent.',
            'C’est aussi l’étape qui prépare au rôle d’animateur, pour ceux qui voudront à leur tour transmettre ce qu’ils ont reçu.',
         ],
      ],
   ];
?>

<div class="mx-auto max-w-7xl">
   <div id="presentation" class="px-6 py-12 lg:px-8 xl:py-20">
      <?= component('page_intro', ['title' => 'Présentation des sections', 'columns' => [
      ['Les Scouts sont un mouvement de jeunesse pluraliste reconnu par la Fédération Wallonie-Bruxelles. Notre unité accueille les jeunes de 6 à 18 ans, répartis en quatre sections selon leur âge.',
       'Le scoutisme aide chaque jeune à grandir et à devenir un citoyen actif, responsable et solidaire. Il repose sur le jeu, la vie en petits groupes, l’apprentissage par l’action et le contact avec la nature.'],
      ['Les activités sont animées par des jeunes bénévoles, formés et accompagnés tout au long de l’année, qui ont à cœur de proposer à chacun une aventure à sa mesure.',
       'La pédagogie, les valeurs et l’histoire du mouvement sont présentées sur le site de la fédération : <a class="link" href="https://lesscouts.be" target="_blank" rel="noopener">www.lesscouts.be</a>'],
   ]]) ?>
   </div>

   <?php foreach (UnitHelper::sections('scout') as $index => $section): ?>
      <?= component('section_presentation', ['section' => $section, 'index' => $index] + $texts[$section['slug']]) ?>
   <?php endforeach; ?>

   <?= component('unit_links', ['unit' => 'scout']) ?>
</div>
<?= $this->endSection() ?>
