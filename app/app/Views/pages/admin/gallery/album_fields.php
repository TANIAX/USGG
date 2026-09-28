<?php
   // Fields of an album. Values: previous submission (validation error), otherwise the album being edited.
   $album = $album ?? null;
   $value = fn(string $field) => old($field) ?? esc((string) ($album->$field ?? ''));
?>
<div>
   <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Titre <span class="text-red-600">*</span></label>
   <input type="text" name="title" id="title" value="<?= $value('title') ?>" required maxlength="255" placeholder="Camp 2026, Hike d'automne..."
      class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
   <div>
      <label for="branch" class="block text-sm font-medium leading-6 text-gray-900">Unité</label>
      <?php if (count($branches) > 1): ?>
         <select name="branch" id="branch" required
            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            <option value="">Choisir...</option>
            <?php foreach ($branches as $code => $label): ?>
               <option value="<?= esc($code) ?>" <?= $value('branch') === $code ? 'selected' : '' ?>><?= esc($label) ?></option>
            <?php endforeach; ?>
         </select>
      <?php else: ?>
         <p class="mt-2 py-1.5 text-sm text-gray-700"><?= esc(reset($branches)) ?></p>
      <?php endif; ?>
   </div>
   <div>
      <label for="album_date" class="block text-sm font-medium leading-6 text-gray-900">Date</label>
      <input type="date" name="album_date" id="album_date" value="<?= $value('album_date') ?>"
         class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
   </div>
</div>

<div>
   <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
   <textarea name="description" id="description" rows="3" maxlength="5000"
      class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"><?= $value('description') ?></textarea>
</div>
