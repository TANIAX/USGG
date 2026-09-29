<?php
   $groups = ['GUIDE' => 'Unité guide', 'SCOUTE' => 'Unité scoute', 'UNITE' => 'Toute l\'unité'];
?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Sections
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
   <div class="px-4 sm:px-6 lg:px-8">
      <?= component('page_header', ['title' => 'Sections', 'subtitle' => 'Nom, logo, couleur, âges et textes de présentation des sections, utilisés dans les menus, l\'accueil, les pages Guides / Scouts, l\'agenda et le staff.']) ?>

      <?= component('flash') ?>

      <div class="space-y-10 pb-16">
         <?php foreach ($groups as $branch => $title): ?>
            <?php $branchSections = array_values(array_filter($sections, fn($section) => $section->branch === $branch)); ?>
            <?php if (!$branchSections) continue; ?>
            <section>
               <h2 class="text-lg font-semibold text-gray-900"><?= $title ?></h2>
               <ul role="list" class="mt-3 divide-y divide-gray-100 rounded-lg bg-white shadow-sm ring-1 ring-gray-200">
                  <?php foreach ($branchSections as $index => $section): ?>
                     <li class="flex flex-wrap items-center gap-x-4 gap-y-2 px-4 py-3 <?= $section->exists ? '' : 'bg-gray-50' ?>">
                        <img src="<?= base_url($section->logo ?: 'assets/img/logo-unite.png') ?>" alt="" class="h-12 w-12 flex-none rounded-full bg-white object-contain p-0.5 ring-1 ring-gray-200">
                        <div class="min-w-0 flex-auto">
                           <p class="flex flex-wrap items-center gap-2 font-semibold <?= $section->exists ? 'text-gray-900' : 'text-gray-400' ?>">
                              <span class="h-2.5 w-2.5 rounded-full" style="background-color: <?= esc($section->color, 'attr') ?>"></span>
                              <?= esc($section->name) ?>
                              <?php if (!$section->exists): ?><?= component('badge', ['label' => 'masquée', 'color' => 'gray-dark', 'shape' => 'tag']) ?><?php endif; ?>
                           </p>
                           <p class="truncate text-sm text-gray-500"><?= esc(implode(' · ', array_filter([$section->group_name, $section->ages, $section->title]))) ?: '—' ?></p>
                        </div>
                        <div class="flex items-center gap-x-1">
                           <?php if ($branch !== 'UNITE'): ?>
                              <?php foreach (['up' => ['chevron-up', 'Monter', $index === 0], 'down' => ['chevron-down', 'Descendre', $index === count($branchSections) - 1]] as $direction => [$icon, $label, $disabled]): ?>
                                 <form method="POST" action="<?= base_url('admin/sections/move/' . $section->id) ?>">
                                    <input type="hidden" name="direction" value="<?= $direction ?>">
                                    <?= component('icon_button', ['icon' => $icon, 'label' => $label, 'attrs' => ['type' => 'submit', 'disabled' => $disabled]]) ?>
                                 </form>
                              <?php endforeach; ?>
                           <?php endif; ?>
                           <a href="<?= base_url('admin/sections/edit/' . $section->id) ?>" class="ml-2 text-sm font-medium text-indigo-600 hover:text-indigo-900">Modifier</a>
                        </div>
                     </li>
                  <?php endforeach; ?>
               </ul>
            </section>
         <?php endforeach; ?>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
