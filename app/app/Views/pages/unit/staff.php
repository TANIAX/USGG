<?php
   // Temporary texts, to be reviewed by the unit. The names and photos of the teams will be added later.
   $units = [
      'guide' => [
         'name' => 'Guides',
         'label' => 'l’unité guide',
         'presentation' => '/guide',
         'sections' => [
            ['id' => 'nutons', 'name' => 'Nutons', 'ages' => '5 à 7 ans', 'logo' => 'assets/img/logo-nutons.png'],
            ['id' => 'lutins', 'name' => 'Lutins', 'ages' => '8 à 11 ans', 'logo' => 'assets/img/logo-lutins.png'],
            ['id' => 'aventures', 'name' => 'Aventures', 'ages' => '11 à 15 ans', 'logo' => 'assets/img/logo-aventures.png'],
            ['id' => 'horizons', 'name' => 'Horizons', 'ages' => '16 à 18 ans', 'logo' => 'assets/img/logo-horizons.png'],
         ],
      ],
      'scout' => [
         'name' => 'Scouts',
         'label' => 'l’unité scoute',
         'presentation' => '/scout',
         'sections' => [
            ['id' => 'baladins', 'name' => 'Baladins', 'ages' => '6 à 8 ans', 'logo' => 'assets/img/logo-baladins.png'],
            ['id' => 'louveteaux', 'name' => 'Louveteaux', 'ages' => '8 à 12 ans', 'logo' => 'assets/img/logo-louveteaux.png'],
            ['id' => 'eclaireurs', 'name' => 'Éclaireurs', 'ages' => '12 à 16 ans', 'logo' => 'assets/img/logo-eclaireurs.png'],
            ['id' => 'pionniers', 'name' => 'Pionniers', 'ages' => '16 à 18 ans', 'logo' => 'assets/img/logo-pionniers.png'],
         ],
      ],
   ];
   $unit = $units[$branch];
?>
<?= $this->extend('pages/default') ?>

<?= $this->section('page_title') ?>
   <?= $unit['name'] ?> - Staff d'unité
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-6 py-12 lg:px-8 xl:py-20">
   <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Staff d'unité</h1>

   <div class="mt-8 grid gap-8 text-lg text-gray-600 lg:grid-cols-2 lg:gap-12">
      <div>
         <p>
            Le staff d’unité coordonne la vie de <?= $unit['label'] ?> : il accompagne les équipes d’animation,
            veille à la cohérence du projet pédagogique et fait le lien avec les parents, l’ASBL et la fédération.
         </p>
         <p class="mt-4">
            Il est composé du chef d’unité et de ses équipiers, tous bénévoles. Ils sont les premiers interlocuteurs
            des familles pour toute question sur l’organisation de l’unité.
         </p>
      </div>
      <div>
         <p>
            Chaque section est animée par sa propre équipe : un animateur responsable et plusieurs animateurs,
            formés au cours de formations reconnues par la Fédération Wallonie-Bruxelles.
         </p>
         <p class="mt-4">
            Vous souhaitez nous rejoindre ou donner un coup de main ? <a href="/contact" class="link">Contactez-nous</a>,
            chaque bonne volonté est la bienvenue.
         </p>
      </div>
   </div>

   <h2 class="mt-16 text-2xl font-semibold text-gray-900">Les équipes d'animation</h2>
   <ul role="list" class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
      <?php foreach ($unit['sections'] as $section): ?>
         <li class="flex flex-col items-center rounded-2xl bg-white p-6 text-center shadow ring-1 ring-gray-200">
            <img class="h-24 w-24 rounded-full bg-white object-contain ring-1 ring-gray-200" src="<?= base_url($section['logo']) ?>" alt="">
            <h3 class="mt-4 font-semibold text-gray-900"><?= $section['name'] ?></h3>
            <p class="text-sm text-gray-500"><?= $section['ages'] ?></p>
            <p class="mt-3 text-sm text-gray-600">L’équipe d’animation des <?= $section['name'] ?> sera présentée ici prochainement.</p>
            <a href="<?= $unit['presentation'] ?>#<?= $section['id'] ?>" class="mt-4 text-sm font-semibold text-indigo-600 hover:text-indigo-500">Découvrir la section</a>
         </li>
      <?php endforeach; ?>
   </ul>
</div>
<?= $this->endSection() ?>
