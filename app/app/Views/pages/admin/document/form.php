<?php
   // Values: previous submission (validation error), otherwise the document being edited
   $name = old('name', null, false) ?? ($document ? pathinfo($document->name, PATHINFO_FILENAME) : '');
   $type = old('file_type', null, false) ?? ($document->file_type ?? '');
   $isActive = old('name') !== null ? old('is_active') === '1' : ($document->is_active ?? true);
?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - <?= $document ? 'Modifier un document' : 'Ajouter un document' ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="{ fileName: '', submitting: false }">
   <div class="px-4 sm:px-6 lg:px-8">
      <?= component('page_header', ['title' => $document ? 'Modifier un document' : 'Ajouter un document', 'back' => ['/admin/document', 'Tous les documents']]) ?>

      <?= component('flash') ?>

      <form method="POST" enctype="multipart/form-data" class="max-w-2xl space-y-6 pb-16" @submit="submitting = true"
         action="<?= $document ? base_url('admin/document/update/' . $document->id) : base_url('admin/document/store') ?>">

         <?= component('field', ['label' => 'Nom du document', 'name' => 'name', 'value' => $name, 'required' => true, 'placeholder' => 'Fiche santé 2026-2027',
            'help' => 'C\'est aussi le nom du fichier téléchargé (l\'extension est ajoutée automatiquement).', 'attrs' => ['minlength' => 2, 'maxlength' => 200]]) ?>

         <?php if (count($types) > 1): ?>
            <?= component('field', ['label' => 'Unité', 'name' => 'file_type', 'type' => 'select', 'required' => true, 'placeholder' => 'Choisir...', 'options' => $types, 'value' => $type]) ?>
         <?php else: ?>
            <div>
               <span class="block text-sm font-medium leading-6 text-gray-900">Unité</span>
               <p class="mt-2 text-sm text-gray-700"><?= esc(reset($types)) ?></p>
            </div>
         <?php endif; ?>

         <!-- File -->
         <div>
            <span class="block text-sm font-medium leading-6 text-gray-900">Fichier <?php if (!$document): ?><span class="text-red-600">*</span><?php endif; ?></span>
            <?php if ($document): ?>
               <p class="mt-2 text-sm text-gray-600">
                  Fichier actuel : <a href="/admin/document/download/<?= $document->id ?>" class="font-medium text-indigo-600 hover:text-indigo-500"><?= esc($document->name) ?></a>
               </p>
            <?php endif; ?>
            <label class="mt-2 flex cursor-pointer items-center justify-center rounded-lg border-2 border-dashed border-gray-300 px-6 py-6 text-center hover:border-indigo-400 hover:bg-gray-50">
               <input type="file" name="file" class="sr-only" accept="<?= esc(implode(',', array_map(fn($ext) => '.' . $ext, $extensions)), 'attr') ?>"
                  @change="fileName = $event.target.files.length ? $event.target.files[0].name : ''" <?= $document ? '' : 'required' ?>>
               <span>
                  <span class="block text-sm font-semibold text-indigo-600" x-text="fileName || '<?= $document ? 'Remplacer le fichier (facultatif)' : 'Choisir le fichier' ?>'"></span>
                  <span class="mt-1 block text-xs text-gray-500"><?= esc(strtoupper(implode(', ', $extensions))) ?> — <?= (int) $maxSizeMb ?> Mo maximum</span>
               </span>
            </label>
         </div>

         <?= component('checkbox', ['variant' => 'panel', 'name' => 'is_active', 'checked' => $isActive, 'label' => 'Document actif',
            'description' => 'Décochez pour le masquer au public (il reste disponible dans l\'administration).']) ?>

         <?= component('form_actions', ['cancel' => '/admin/document', 'submit_attrs' => [':disabled' => 'submitting']]) ?>
      </form>
   </div>
</div>
<?= $this->endSection() ?>
