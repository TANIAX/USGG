<?php
   // Values: previous submission (validation error), otherwise the document being edited
   $name = old('name') ?? esc($document ? pathinfo($document->name, PATHINFO_FILENAME) : '');
   $type = old('file_type') ?? ($document->file_type ?? '');
   $isActive = old('name') !== null ? old('is_active') === '1' : ($document->is_active ?? true);
   $inputClass = 'mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6';
?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - <?= $document ? 'Modifier un document' : 'Ajouter un document' ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="{ fileName: '', submitting: false }">
   <div class="px-4 sm:px-6 lg:px-8">
      <div class="mt-12 mb-8 border-b py-4">
         <a href="/admin/document" class="text-sm font-medium text-gray-500 hover:text-gray-700">&larr; Tous les documents</a>
         <h1 class="mt-2 font-semibold text-4xl leading-tight text-gray-900"><?= $document ? 'Modifier un document' : 'Ajouter un document' ?></h1>
      </div>

      <?= $this->include('pages/admin/messages') ?>

      <form method="POST" enctype="multipart/form-data" class="max-w-2xl space-y-6 pb-16" @submit="submitting = true"
         action="<?= $document ? base_url('admin/document/update/' . $document->id) : base_url('admin/document/store') ?>">

         <div>
            <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Nom du document <span class="text-red-600">*</span></label>
            <input type="text" name="name" id="name" value="<?= $name ?>" required minlength="2" maxlength="200" placeholder="Fiche santé 2026-2027" class="<?= $inputClass ?>">
            <p class="mt-1 text-xs text-gray-500">C'est aussi le nom du fichier téléchargé (l'extension est ajoutée automatiquement).</p>
         </div>

         <div>
            <label for="file_type" class="block text-sm font-medium leading-6 text-gray-900">Unité</label>
            <?php if (count($types) > 1): ?>
               <select name="file_type" id="file_type" required class="<?= $inputClass ?>">
                  <option value="">Choisir...</option>
                  <?php foreach ($types as $code => $label): ?>
                     <option value="<?= esc($code) ?>" <?= $type === $code ? 'selected' : '' ?>><?= esc($label) ?></option>
                  <?php endforeach; ?>
               </select>
            <?php else: ?>
               <p class="mt-2 text-sm text-gray-700"><?= esc(reset($types)) ?></p>
            <?php endif; ?>
         </div>

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

         <!-- Visibility -->
         <div class="rounded-lg bg-gray-50 p-4">
            <label class="flex items-start gap-x-3">
               <input type="checkbox" name="is_active" value="1" <?= $isActive ? 'checked' : '' ?> class="mt-0.5 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
               <span>
                  <span class="block text-sm font-medium text-gray-900">Document actif</span>
                  <span class="block text-sm text-gray-500">Décochez pour le masquer au public (il reste disponible dans l'administration).</span>
               </span>
            </label>
         </div>

         <div class="flex justify-end gap-x-3 border-t pt-6">
            <a href="/admin/document" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Annuler</a>
            <button type="submit" :disabled="submitting" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 disabled:opacity-50">Enregistrer</button>
         </div>
      </form>
   </div>
</div>
<?= $this->endSection() ?>
