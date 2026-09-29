<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Nouvel album
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
   <div class="px-4 sm:px-6 lg:px-8">
      <?= component('page_header', ['title' => 'Nouvel album', 'back' => ['/admin/galerie', 'Tous les albums']]) ?>

      <?= component('flash') ?>

      <form method="POST" action="<?= base_url('admin/galerie/store') ?>" class="max-w-3xl space-y-6">
         <?= $this->include('pages/admin/gallery/album_fields') ?>

         <p class="text-sm text-gray-500">Les photos s'ajoutent à l'étape suivante.</p>

         <?= component('form_actions', ['cancel' => '/admin/galerie', 'submit' => 'Créer l\'album']) ?>
      </form>
   </div>
</div>
<?= $this->endSection() ?>
