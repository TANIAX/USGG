<?php
   // Names used several times (duplicates to merge)
   $counts = array_count_values(array_map(fn($type) => mb_strtolower($type->name), $types));
?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Fonctions
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
   <div class="px-4 sm:px-6 lg:px-8">
      <?= component('page_header', ['title' => 'Fonctions', 'subtitle' => 'Fonctions choisies pour les comptes et les responsables de section. Pour supprimer un doublon, fusionnez-le dans la bonne fonction : les fiches concernées sont déplacées.']) ?>

      <?= component('flash') ?>

      <form method="POST" action="<?= base_url('admin/fonctions/save') ?>" class="mb-8 flex max-w-xl items-end gap-x-3">
         <?= component('field', ['label' => 'Nouvelle fonction', 'name' => 'name', 'required' => true, 'placeholder' => 'Trésorière, Intendant...', 'class' => 'flex-auto', 'attrs' => ['maxlength' => 100]]) ?>
         <?= component('button', ['label' => 'Ajouter', 'type' => 'submit']) ?>
      </form>

      <ul role="list" class="mb-16 max-w-4xl divide-y divide-gray-100 rounded-lg bg-white shadow-sm ring-1 ring-gray-200">
         <?php foreach ($types as $type): ?>
            <li class="px-4 py-3" x-data="{ editing: false, merging: false }">
               <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                  <div class="min-w-0 flex-auto">
                     <p class="font-medium text-gray-900">
                        <?= esc($type->name) ?>
                        <?php if ($counts[mb_strtolower($type->name)] > 1): ?><?= component('badge', ['label' => 'doublon', 'color' => 'amber', 'shape' => 'tag', 'class' => 'ml-1']) ?><?php endif; ?>
                     </p>
                     <p class="text-sm text-gray-500"><?= $type->users ?> compte<?= $type->users > 1 ? 's' : '' ?> · <?= $type->leaders ?> responsable<?= $type->leaders > 1 ? 's' : '' ?></p>
                  </div>
                  <div class="flex items-center gap-x-4 text-sm font-medium">
                     <button type="button" @click="editing = !editing; merging = false" class="text-indigo-600 hover:text-indigo-900">Renommer</button>
                     <?php if (count($types) > 1): ?>
                        <button type="button" @click="merging = !merging; editing = false" class="text-gray-600 hover:text-gray-900">Fusionner</button>
                     <?php endif; ?>
                     <?php if ($type->users + $type->leaders === 0): ?>
                        <form method="POST" action="<?= base_url('admin/fonctions/delete/' . $type->id) ?>" onsubmit="return confirm('Supprimer la fonction « <?= esc($type->name, 'js') ?> » ?')">
                           <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                        </form>
                     <?php endif; ?>
                  </div>
               </div>
               <form x-show="editing" x-cloak method="POST" action="<?= base_url('admin/fonctions/save/' . $type->id) ?>" class="mt-3 flex max-w-xl items-center gap-x-3">
                  <?= component('input', ['name' => 'name', 'id' => false, 'value' => $type->name, 'required' => true, 'attrs' => ['maxlength' => 100, 'aria-label' => 'Nouveau nom']]) ?>
                  <?= component('button', ['label' => 'Enregistrer', 'type' => 'submit', 'size' => 'sm', 'class' => 'flex-none']) ?>
               </form>
               <form x-show="merging" x-cloak method="POST" action="<?= base_url('admin/fonctions/merge/' . $type->id) ?>" class="mt-3 flex max-w-xl flex-wrap items-center gap-3"
                  onsubmit="return confirm('Fusionner « <?= esc($type->name, 'js') ?> » dans la fonction choisie ? Elle sera supprimée.')">
                  <span class="text-sm text-gray-600">Fusionner dans</span>
                  <?= component('input', ['type' => 'select', 'name' => 'target', 'id' => false, 'required' => true, 'placeholder' => 'Choisir...', 'width' => 'w-56',
                     'options' => array_column(array_filter($types, fn($other) => $other->id !== $type->id), 'name', 'id'), 'attrs' => ['aria-label' => 'Fonction qui la remplace']]) ?>
                  <?= component('button', ['label' => 'Fusionner', 'type' => 'submit', 'size' => 'sm', 'variant' => 'secondary']) ?>
               </form>
            </li>
         <?php endforeach; ?>
      </ul>
   </div>
</div>
<?= $this->endSection() ?>
