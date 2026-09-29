<?php
   // Temporary texts, to be reviewed by the unit
   use App\Helpers\UnitHelper;

   $unit = UnitHelper::UNITS[$branch];

   // Leaders by section (slug); no personal contact information is shown
   $leadersBySection = [];
   foreach ($leaders as $leader) {
      $leadersBySection[$leader->section_slug][] = $leader;
   }
?>
<?= $this->extend('pages/default') ?>

<?= $this->section('page_title') ?>
   <?= $unit['name'] ?> - Staff d'unité
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-6 py-12 lg:px-8 xl:py-20">
   <?= component('page_intro', ['title' => 'Staff d\'unité', 'columns' => [
      ['Le staff d’unité coordonne la vie de ' . $unit['label'] . ' : il accompagne les équipes d’animation, veille à la cohérence du projet pédagogique et fait le lien avec les parents, l’ASBL et la fédération.',
       'Il est composé du chef d’unité et de ses équipiers, tous bénévoles. Ils sont les premiers interlocuteurs des familles pour toute question sur l’organisation de l’unité.'],
      ['Chaque section est animée par sa propre équipe : un animateur responsable et plusieurs animateurs, formés au cours de formations reconnues par la Fédération Wallonie-Bruxelles.',
       'Vous souhaitez nous rejoindre ou donner un coup de main ? <a href="/contact" class="link">Contactez-nous</a>, chaque bonne volonté est la bienvenue.'],
   ]]) ?>

   <!-- Unit staff (section "Unité") -->
   <?php if (!empty($leadersBySection['unite'])): ?>
      <h2 class="mt-16 text-2xl font-semibold text-gray-900">Le staff d'unité</h2>
      <ul role="list" class="mt-6 grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-5">
         <?php foreach ($leadersBySection['unite'] as $leader): ?>
            <?= component('leader', ['leader' => $leader]) ?>
         <?php endforeach; ?>
      </ul>
   <?php endif; ?>

   <h2 class="mt-16 text-2xl font-semibold text-gray-900">Les équipes d'animation</h2>
   <ul role="list" class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
      <?php foreach (UnitHelper::sections($branch) as $section): ?>
         <li class="flex flex-col items-center rounded-2xl bg-white p-6 text-center shadow ring-1 ring-gray-200">
            <img class="h-24 w-24 rounded-full bg-white object-contain ring-1 ring-gray-200" src="<?= base_url($section['logo']) ?>" alt="">
            <h3 class="mt-4 font-semibold text-gray-900"><?= esc($section['name']) ?></h3>
            <p class="text-sm text-gray-500"><?= esc($section['ages']) ?></p>
            <?php if (!empty($leadersBySection[$section['slug']])): ?>
               <ul role="list" class="mt-4 w-full space-y-3 text-left">
                  <?php foreach ($leadersBySection[$section['slug']] as $leader): ?>
                     <?= component('leader', ['leader' => $leader, 'variant' => 'row']) ?>
                  <?php endforeach; ?>
               </ul>
            <?php else: ?>
               <p class="mt-3 text-sm text-gray-600">L’équipe d’animation des <?= esc($section['name']) ?> sera présentée ici prochainement.</p>
            <?php endif; ?>
            <a href="<?= $section['href'] ?>" class="mt-4 text-sm font-semibold text-indigo-600 hover:text-indigo-500">Découvrir la section</a>
         </li>
      <?php endforeach; ?>
   </ul>
</div>
<?= $this->endSection() ?>
