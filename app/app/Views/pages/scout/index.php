<?= $this->extend('pages/default') ?>

<?= $this->section('page_title') ?>
   Scouts - Présentation des sections
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
   use App\Helpers\UnitHelper;

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
      <?= component('section_presentation', ['section' => $section, 'index' => $index, 'title' => $section['title'], 'subtitle' => $section['subtitle'], 'paragraphs' => $section['paragraphs']]) ?>
   <?php endforeach; ?>

   <?= component('unit_links', ['unit' => 'scout']) ?>
</div>
<?= $this->endSection() ?>
