<?php
   use App\Controllers\ContentController;

   $isFaq = $type === 'faq';
   $value = fn(string $field) => old($field, null, false) ?? (string) ($item->$field ?? '');
   $active = old($isFaq ? 'question' : 'name') !== null ? old('is_active') === '1' : ($item->is_active ?? true);
   $title = $isFaq ? ($item ? 'Modifier la question' : 'Nouvelle question') : ($item ? 'Modifier le témoignage' : 'Nouveau témoignage');
   $action = base_url('admin/contenus/' . $type . '/save' . ($item ? '/' . $item->id : ''));
   $picture = $item ? ContentController::pictureUrl($item->picture) : null;
?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - <?= $title ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
   <div class="px-4 sm:px-6 lg:px-8">
      <?= component('page_header', ['title' => $title, 'back' => ['/admin/contenus#' . $type, 'FAQ et témoignages']]) ?>

      <?= component('flash') ?>

      <form method="POST" enctype="multipart/form-data" action="<?= $action ?>" class="max-w-3xl space-y-6 pb-16" x-data="{ picture: <?= esc(js_data($picture), 'attr') ?> }">
         <?php if ($isFaq): ?>
            <?= component('field', ['label' => 'Question', 'name' => 'question', 'required' => true, 'value' => $value('question'), 'attrs' => ['maxlength' => 255]]) ?>
            <?= component('field', ['label' => 'Réponse', 'name' => 'answer', 'type' => 'textarea', 'rows' => 6, 'required' => true, 'value' => $value('answer'), 'attrs' => ['maxlength' => 5000]]) ?>
         <?php else: ?>
            <?= component('field', ['label' => 'Témoignage', 'name' => 'quote', 'type' => 'textarea', 'rows' => 5, 'required' => true, 'value' => $value('quote'),
               'help' => 'Sans guillemets : ils sont ajoutés à l\'affichage.', 'attrs' => ['maxlength' => 1000]]) ?>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
               <?= component('field', ['label' => 'Nom', 'name' => 'name', 'required' => true, 'value' => $value('name'), 'placeholder' => 'Prénom Nom', 'attrs' => ['maxlength' => 100]]) ?>
               <?= component('field', ['label' => 'Totem', 'name' => 'totem', 'value' => $value('totem'), 'attrs' => ['maxlength' => 100]]) ?>
            </div>
            <div>
               <span class="block text-sm font-medium leading-6 text-gray-900">Photo</span>
               <div class="mt-2 flex flex-wrap items-center gap-4">
                  <img x-show="picture" :src="picture" alt="" class="h-16 w-16 rounded-full bg-gray-100 object-cover">
                  <?= component('file_button', ['name' => 'picture', 'label_alpine' => "picture ? 'Changer la photo' : 'Choisir une photo'", 'attrs' => ['@change' => 'if ($event.target.files.length) picture = URL.createObjectURL($event.target.files[0])']]) ?>
                  <?php if ($picture): ?>
                     <?= component('checkbox', ['name' => 'remove_picture', 'label' => 'Retirer la photo', 'attrs' => ['@change' => 'if ($event.target.checked) picture = null']]) ?>
                  <?php endif; ?>
               </div>
               <p class="mt-2 text-sm text-gray-500">Facultatif : sans photo, l'initiale du nom est affichée.</p>
            </div>
         <?php endif; ?>

         <?= component('checkbox', ['variant' => 'panel', 'name' => 'is_active', 'checked' => $active, 'label' => $isFaq ? 'Question affichée' : 'Témoignage affiché',
            'description' => 'Décochez pour le garder sans l\'afficher sur l\'accueil.']) ?>

         <?= component('form_actions', ['cancel' => '/admin/contenus#' . $type]) ?>
      </form>
   </div>
</div>
<?= $this->endSection() ?>
