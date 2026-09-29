<?php use App\Helpers\DateHelper; ?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - <?= esc($page->title) ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-6 py-12 sm:py-16 lg:px-8">
   <div class="lg:grid lg:grid-cols-12 lg:gap-x-12">
      <!-- Table of contents (titles "##" of the text) -->
      <?php if (count($headings) > 2): ?>
         <nav class="mb-10 lg:col-span-3 lg:mb-0" aria-label="Sommaire">
            <div class="rounded-lg bg-gray-50 p-4 lg:sticky lg:top-8">
               <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Sommaire</p>
               <ol class="mt-3 space-y-2 text-sm">
                  <?php foreach ($headings as [$anchor, $title]): ?>
                     <li><a href="#<?= esc($anchor, 'attr') ?>" class="text-gray-700 hover:text-indigo-600"><?= esc($title) ?></a></li>
                  <?php endforeach; ?>
               </ol>
            </div>
         </nav>
      <?php endif; ?>

      <article class="<?= count($headings) > 2 ? 'lg:col-span-9' : 'lg:col-span-12' ?> max-w-3xl">
         <h1 class="text-4xl font-bold tracking-tight text-gray-900"><?= esc($page->title) ?></h1>
         <?php if ($page->updated_at): ?>
            <p class="mt-2 text-sm text-gray-500">Dernière mise à jour : <?= esc(DateHelper::french($page->updated_at)) ?></p>
         <?php endif; ?>
         <div class="page-text mt-8"><?= $html ?></div>
      </article>
   </div>
</div>
<?= $this->endSection() ?>
