<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Nouvel album
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
   <div class="px-4 sm:px-6 lg:px-8">
      <div class="mt-12 mb-8 border-b py-4">
         <h1 class="font-semibold text-4xl leading-tight text-gray-900">Nouvel album</h1>
      </div>

      <?= $this->include('pages/admin/messages') ?>

      <form method="POST" action="<?= base_url('admin/galerie/store') ?>" class="max-w-3xl space-y-6">
         <?= $this->include('pages/admin/gallery/album_fields') ?>

         <p class="text-sm text-gray-500">Les photos s'ajoutent à l'étape suivante.</p>

         <div class="flex justify-end gap-x-3 border-t pt-6">
            <a href="/admin/galerie" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Annuler</a>
            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Créer l'album</button>
         </div>
      </form>
   </div>
</div>
<?= $this->endSection() ?>
