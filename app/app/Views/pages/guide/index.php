<?= $this->extend('pages/default') ?>

<?= $this->section('page_title') ?>
   Guides - Présentation des sections
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
   use App\Helpers\UnitHelper;

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
      <?= component('section_presentation', ['section' => $section, 'index' => $index, 'title' => $section['title'], 'subtitle' => $section['subtitle'], 'paragraphs' => $section['paragraphs']]) ?>
   <?php endforeach; ?>

   <?= component('unit_links', ['unit' => 'guide']) ?>
</div>
<?= $this->endSection() ?>
