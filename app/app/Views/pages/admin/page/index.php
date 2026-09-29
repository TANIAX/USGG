<?php use App\Helpers\DateHelper; ?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Charte et confidentialité
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
   <div class="px-4 sm:px-6 lg:px-8">
      <?= component('page_header', ['title' => 'Charte et confidentialité', 'subtitle' => 'Textes publiés sur le site et reliés depuis le pied de page et les formulaires (inscription, contact, newsletter).']) ?>

      <?= component('flash') ?>

      <ul role="list" class="mb-16 max-w-3xl divide-y divide-gray-100 rounded-lg bg-white shadow-sm ring-1 ring-gray-200">
         <?php foreach ($pages as $page): ?>
            <li class="flex flex-wrap items-center gap-x-4 gap-y-2 px-4 py-4">
               <div class="min-w-0 flex-auto">
                  <p class="font-semibold text-gray-900"><?= esc($page->title) ?></p>
                  <p class="text-sm text-gray-500">
                     <?= esc($urls[$page->slug] ?? '') ?>
                     <?php if ($page->updated_at): ?>
                        · modifiée le <?= esc(DateHelper::french($page->updated_at)) ?><?= ($page->updated_by_totem ?: $page->updated_by_firstname) ? ' par ' . esc($page->updated_by_totem ?: $page->updated_by_firstname) : '' ?>
                     <?php endif; ?>
                  </p>
               </div>
               <div class="flex items-center gap-x-4 text-sm font-medium">
                  <a href="<?= base_url(ltrim($urls[$page->slug] ?? '', '/')) ?>" target="_blank" class="text-gray-600 hover:text-gray-900">Voir</a>
                  <a href="<?= base_url('admin/pages/' . $page->slug) ?>" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
               </div>
            </li>
         <?php endforeach; ?>
      </ul>
   </div>
</div>
<?= $this->endSection() ?>
