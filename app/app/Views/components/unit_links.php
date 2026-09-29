<?php
/**
 * Links at the bottom of the presentation of a unit (staff, documents, photos, registration). Props: unit ("guide" or "scout").
 */
?>
<div class="bg-white px-6 pb-20 lg:px-8">
   <div class="mx-auto flex max-w-7xl flex-wrap gap-3">
      <?= component('button', ['label' => 'Le staff de l’unité', 'href' => '/' . $unit . '/staff']) ?>
      <?= component('button', ['label' => 'Documents', 'href' => '/' . $unit . '/document', 'variant' => 'secondary']) ?>
      <?= component('button', ['label' => 'Galerie photos', 'href' => '/galerie/' . $unit, 'variant' => 'secondary']) ?>
      <?= component('button', ['label' => 'Inscrire un enfant', 'href' => '/en-pratique/inscription', 'variant' => 'secondary']) ?>
   </div>
</div>
