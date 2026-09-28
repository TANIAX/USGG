<?= $this->extend('pages/default') ?>

<?= $this->section('page_title') ?>
   Scouts - Présentation des sections
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
   // Temporary texts, to be reviewed by the unit
   $sections = [
      [
         'id' => 'baladins',
         'title' => 'Baladins',
         'subtitle' => 'La Ribambelle · 6 à 8 ans',
         'logo' => 'assets/img/logo-baladins.png',
         'paragraphs' => [
            'Chez les Baladins, les plus jeunes découvrent le scoutisme par le jeu, l’imaginaire et la découverte de la nature. Chaque réunion est une petite aventure, pensée pour leur âge et leur rythme.',
            'Les animateurs veillent à ce que chacun trouve sa place dans le groupe, apprenne à vivre avec les autres et fasse ses premiers pas vers l’autonomie.',
            'L’année se termine par un camp de quelques jours, souvent la première expérience de vie en groupe loin de la maison.',
         ],
      ],
      [
         'id' => 'louveteaux',
         'title' => 'Louveteaux',
         'subtitle' => 'La Meute · 8 à 12 ans',
         'logo' => 'assets/img/logo-louveteaux.png',
         'paragraphs' => [
            'Aux Louveteaux, les jeunes vivent en Meute et en sizaines, de petites équipes où chacun peut prendre une responsabilité. L’univers du Livre de la Jungle accompagne leurs aventures.',
            'Grands jeux, bricolages, activités en forêt et veillées rythment l’année. Les louveteaux apprennent à coopérer, à se dépasser et à respecter les autres.',
            'Au camp d’été, la Meute vit une dizaine de jours ensemble : un moment fort, attendu toute l’année.',
         ],
      ],
      [
         'id' => 'eclaireurs',
         'title' => 'Éclaireurs',
         'subtitle' => 'La Troupe · 12 à 16 ans',
         'logo' => 'assets/img/logo-eclaireurs.png',
         'paragraphs' => [
            'Chez les Éclaireurs, la Troupe s’organise en patrouilles. Les jeunes prennent de plus en plus d’initiatives : ils préparent des activités, gèrent leur matériel et apprennent à vivre en autonomie.',
            'Hikes, constructions, techniques scoutes et vie dans la nature font partie du quotidien. C’est l’âge des défis, de la débrouille et de la solidarité au sein de la patrouille.',
            'Le camp sous tente est le point d’orgue de l’année : on y construit son lieu de vie et on y vit l’aventure pour de vrai.',
         ],
      ],
      [
         'id' => 'pionniers',
         'title' => 'Pionniers',
         'subtitle' => 'Le Poste · 16 à 18 ans',
         'logo' => 'assets/img/logo-pionniers.png',
         'paragraphs' => [
            'Aux Pionniers, les jeunes construisent ensemble les projets qui les animent : service à la communauté, découverte d’autres cultures, activités qui ont du sens pour eux.',
            'Le Poste apprend à s’organiser, à débattre, à financer ses projets et à les mener jusqu’au bout. Les animateurs accompagnent plus qu’ils ne dirigent.',
            'C’est aussi l’étape qui prépare au rôle d’animateur, pour ceux qui voudront à leur tour transmettre ce qu’ils ont reçu.',
         ],
      ],
   ];
?>

<div class="mx-auto max-w-7xl">
   <!-- Présentation -->
   <div id="presentation" class="overflow-hidden bg-white px-6 py-8 lg:px-8 xl:py-24">
      <div class="mx-auto max-w-max lg:max-w-7xl">
         <h1 class="mt-2 text-3xl/8 font-bold tracking-tight text-gray-900 sm:text-4xl">Présentation des sections</h1>
         <div class="mt-8 lg:grid lg:grid-cols-2 lg:gap-12 text-lg text-gray-500">
            <div>
               <p>
                  Les Scouts sont un mouvement de jeunesse pluraliste reconnu par la Fédération Wallonie-Bruxelles.
                  Notre unité accueille les jeunes de 6 à 18 ans, répartis en quatre sections selon leur âge.
               </p>
               <p class="mt-4">
                  Le scoutisme aide chaque jeune à grandir et à devenir un citoyen actif, responsable et solidaire.
                  Il repose sur le jeu, la vie en petits groupes, l’apprentissage par l’action et le contact avec la nature.
               </p>
            </div>
            <div class="mt-4 lg:mt-0">
               <p>
                  Les activités sont animées par des jeunes bénévoles, formés et accompagnés tout au long de l’année,
                  qui ont à cœur de proposer à chacun une aventure à sa mesure.
               </p>
               <p class="mt-4">
                  La pédagogie, les valeurs et l’histoire du mouvement sont présentées sur le site de la fédération :
                  <a class="link" href="https://lesscouts.be" target="_blank" rel="noopener">www.lesscouts.be</a>
               </p>
            </div>
         </div>
      </div>
   </div>

   <?php foreach ($sections as $index => $section): ?>
      <!-- <?= $section['title'] ?> -->
      <div id="<?= $section['id'] ?>" class="bg-white py-16">
         <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto grid max-w-2xl grid-cols-1 items-start gap-x-8 gap-y-16 lg:mx-0 lg:max-w-none lg:grid-cols-2">
               <!-- Desktop image (alternately on the left and on the right) -->
               <div class="hidden lg:block lg:pr-4 <?= $index % 2 ? 'lg:order-last' : '' ?>">
                  <div class="relative mx-auto aspect-square w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl">
                     <img class="absolute inset-0 h-full w-full object-contain" src="<?= base_url($section['logo']) ?>" alt="Logo des <?= $section['title'] ?>">
                  </div>
               </div>
               <div class="text-base/7 text-gray-700 lg:max-w-lg">
                  <h2 class="text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl"><?= $section['title'] ?></h2>
                  <p class="mt-2 text-lg font-medium text-indigo-600"><?= $section['subtitle'] ?></p>
                  <!-- Mobile image -->
                  <div class="relative mx-auto my-6 aspect-square w-48 overflow-hidden rounded-3xl bg-white shadow-xl sm:w-56 lg:hidden">
                     <img class="absolute inset-0 h-full w-full object-contain" src="<?= base_url($section['logo']) ?>" alt="Logo des <?= $section['title'] ?>">
                  </div>
                  <div class="max-w-xl">
                     <?php foreach ($section['paragraphs'] as $paragraph): ?>
                        <p class="mt-6"><?= $paragraph ?></p>
                     <?php endforeach; ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   <?php endforeach; ?>

   <!-- Links -->
   <div class="bg-white px-6 pb-20 lg:px-8">
      <div class="mx-auto flex max-w-7xl flex-wrap gap-3">
         <a href="/scout/staff" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Le staff de l’unité</a>
         <a href="/scout/document" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Documents</a>
         <a href="/galerie/scout" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Galerie photos</a>
         <a href="/en-pratique/inscription" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Inscrire un enfant</a>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
