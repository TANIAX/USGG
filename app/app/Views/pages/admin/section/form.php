<?php
   $value = fn(string $field) => old($field, null, false) ?? (string) ($section->$field ?? '');
   $isUnit = $section->slug === 'unite';
   $page = ['GUIDE' => '/guide', 'SCOUTE' => '/scout'][$section->branch] ?? null;
?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Section <?= esc($section->name) ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="{ logo: '<?= esc(base_url($section->logo ?: 'assets/img/logo-unite.png'), 'js') ?>', color: '<?= esc($value('color') ?: '#6366f1', 'js') ?>' }">
   <div class="px-4 sm:px-6 lg:px-8">
      <?php component_open('page_header', ['title' => $section->name, 'back' => ['/admin/sections', 'Toutes les sections'],
         'subtitle' => $page ? 'Présentée sur la page ' . $page . '#' . $section->slug : 'Section utilisée pour le staff d\'unité et les événements de toute l\'unité.']) ?>
         <?php if ($page): ?>
            <?= component('button', ['label' => 'Voir la présentation', 'href' => $page . '#' . $section->slug, 'variant' => 'secondary', 'size' => 'sm', 'block' => true, 'attrs' => ['target' => '_blank']]) ?>
         <?php endif; ?>
      <?= component_close() ?>

      <?= component('flash') ?>

      <form method="POST" enctype="multipart/form-data" action="<?= base_url('admin/sections/update/' . $section->id) ?>" class="max-w-3xl space-y-8 pb-16">
         <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <?= component('field', ['label' => 'Nom', 'name' => 'name', 'required' => true, 'value' => $value('name'), 'class' => 'sm:col-span-2', 'attrs' => ['maxlength' => 100]]) ?>
            <?php component_open('field', ['label' => 'Couleur', 'for' => 'color', 'required' => true]) ?>
               <div class="mt-2 flex items-center gap-x-3">
                  <input type="color" id="color" name="color" x-model="color" class="h-9 w-14 cursor-pointer rounded border-0 bg-transparent p-0">
                  <span class="font-mono text-sm text-gray-600" x-text="color"></span>
               </div>
            <?= component_close() ?>
         </div>

         <div>
            <span class="block text-sm font-medium leading-6 text-gray-900">Logo</span>
            <div class="mt-2 flex items-center gap-x-4">
               <img :src="logo" alt="" class="h-20 w-20 rounded-full bg-white object-contain p-1 ring-2" :style="`--tw-ring-color: ${color}`">
               <?= component('file_button', ['name' => 'logo', 'label' => 'Changer le logo', 'attrs' => ['@change' => 'if ($event.target.files.length) logo = URL.createObjectURL($event.target.files[0])']]) ?>
            </div>
            <p class="mt-2 text-sm text-gray-500">PNG avec fond transparent conseillé. L'image est réduite à 512 pixels.</p>
         </div>

         <?php if (!$isUnit): ?>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
               <?= component('field', ['label' => 'Nom du groupe', 'name' => 'group_name', 'value' => $value('group_name'), 'placeholder' => 'La Meute, La Ronde...', 'attrs' => ['maxlength' => 100]]) ?>
               <?= component('field', ['label' => 'Âges', 'name' => 'ages', 'value' => $value('ages'), 'placeholder' => '8 à 12 ans', 'attrs' => ['maxlength' => 50]]) ?>
            </div>
            <?= component('field', ['label' => 'Titre de la présentation', 'name' => 'title', 'value' => $value('title'), 'placeholder' => $section->name,
               'help' => 'Facultatif : le nom de la section est utilisé sinon (ex. « Nutons ami de tous »).', 'attrs' => ['maxlength' => 150]]) ?>
            <?= component('field', ['label' => 'Texte de présentation', 'name' => 'description', 'type' => 'textarea', 'rows' => 12, 'value' => $value('description'),
               'help' => 'Laissez une ligne vide entre deux paragraphes.', 'attrs' => ['maxlength' => 10000]]) ?>

            <?= component('checkbox', ['variant' => 'panel', 'name' => 'exists', 'checked' => (bool) (old('name') !== null ? old('exists') : $section->exists),
               'label' => 'Section active', 'description' => 'Décochez pour la masquer du site (menus, accueil, présentation, formulaires). Les événements et responsables sont conservés.']) ?>
         <?php else: ?>
            <input type="hidden" name="exists" value="1">
         <?php endif; ?>

         <?= component('form_actions', ['cancel' => '/admin/sections']) ?>
      </form>
   </div>
</div>
<?= $this->endSection() ?>
