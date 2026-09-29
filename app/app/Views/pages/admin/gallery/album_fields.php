<?php
   // Fields of an album. Values: previous submission (validation error), otherwise the album being edited.
   $album = $album ?? null;
   $value = fn(string $field) => old($field, null, false) ?? (string) ($album->$field ?? '');
?>
<?= component('field', ['label' => 'Titre', 'name' => 'title', 'required' => true, 'value' => $value('title'), 'placeholder' => 'Camp 2026, Hike d\'automne...', 'attrs' => ['maxlength' => 255]]) ?>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
   <?php if (count($branches) > 1): ?>
      <?= component('field', ['label' => 'Unité', 'name' => 'branch', 'type' => 'select', 'required' => true, 'placeholder' => 'Choisir...', 'options' => $branches, 'value' => $value('branch')]) ?>
   <?php else: ?>
      <div>
         <span class="block text-sm font-medium leading-6 text-gray-900">Unité</span>
         <p class="mt-2 py-1.5 text-sm text-gray-700"><?= esc(reset($branches)) ?></p>
      </div>
   <?php endif; ?>
   <?= component('field', ['label' => 'Date', 'name' => 'album_date', 'type' => 'date', 'value' => $value('album_date')]) ?>
</div>

<?= component('field', ['label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 3, 'value' => $value('description'), 'attrs' => ['maxlength' => 5000]]) ?>
