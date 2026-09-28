<?= $this->extend('pages/default') ?>

<?= $this->section('page_title') ?>
   Guides et scoutes de Gosselies - L'ASBL
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
   // Temporary texts, to be reviewed by the ASBL
   $missions = [
      ['title' => 'Les locaux', 'text' => 'L’ASBL est responsable des bâtiments où se déroulent les réunions : entretien, travaux, sécurité et mise à disposition des sections.'],
      ['title' => 'Le matériel', 'text' => 'Tentes, matériel de camp, de cuisine et de jeux : l’ASBL gère et renouvelle le matériel commun de l’unité.'],
      ['title' => 'La gestion financière', 'text' => 'Elle tient les comptes, gère les cotisations, les assurances et soutient financièrement les projets des sections.'],
      ['title' => 'Les événements', 'text' => 'Soupers, ventes et fête d’unité : l’ASBL organise les événements qui rassemblent les familles et financent la vie de l’unité.'],
   ];
?>
<div class="mx-auto max-w-7xl px-6 py-12 lg:px-8 xl:py-20">
   <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">L'ASBL de l'unité</h1>

   <div class="mt-8 grid gap-8 text-lg text-gray-600 lg:grid-cols-2 lg:gap-12">
      <div>
         <p>
            L’ASBL soutient l’unité guide et l’unité scoute de Gosselies. Elle prend en charge tout ce qui permet aux
            animateurs de se concentrer sur l’essentiel : proposer aux jeunes des activités de qualité, en toute sécurité.
         </p>
         <p class="mt-4">
            Elle est composée de parents, d’anciens et d’amis de l’unité, tous bénévoles, réunis au sein d’un conseil
            d’administration (président, vice-président, trésorier et administrateurs).
         </p>
      </div>
      <div>
         <p>
            Les locaux de l’unité se trouvent Rue Henri Belyn 77, à Gosselies.
         </p>
         <p class="mt-4">
            Vous souhaitez vous investir, proposer une aide ponctuelle ou soutenir l’unité ?
            <a href="/contact" class="link">Contactez-nous</a> : toutes les compétences sont utiles.
         </p>
      </div>
   </div>

   <h2 class="mt-16 text-2xl font-semibold text-gray-900">Ses missions</h2>
   <dl class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
      <?php foreach ($missions as $mission): ?>
         <div class="rounded-2xl bg-white p-6 shadow ring-1 ring-gray-200">
            <dt class="font-semibold text-gray-900"><?= $mission['title'] ?></dt>
            <dd class="mt-2 text-sm leading-6 text-gray-600"><?= $mission['text'] ?></dd>
         </div>
      <?php endforeach; ?>
   </dl>

   <div class="mt-12 flex flex-wrap gap-3">
      <a href="/asbl/evenements" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Nos événements</a>
      <a href="/en-pratique/cotisation" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cotisations</a>
      <a href="/contact" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Contact</a>
   </div>
</div>
<?= $this->endSection() ?>
