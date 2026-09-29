<?php
/**
 * Presentation of a section: logo (alternately on the left and on the right) and text. Props:
 *  - section: section of UnitHelper::sections() (slug = anchor, name, logo)
 *  - index: position in the page (alternates the side of the logo)
 *  - title (default: name of the section), subtitle (optional)
 *  - paragraphs: paragraphs (html written in the views)
 */
$title = $title ?? $section['name'];
$logo = '<img class="absolute inset-0 h-full w-full object-contain" src="' . base_url($section['logo']) . '" alt="Logo des ' . esc($section['name'], 'attr') . '">';
?>
<div id="<?= esc($section['slug'], 'attr') ?>" class="bg-white py-16">
   <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="mx-auto grid max-w-2xl grid-cols-1 items-start gap-x-8 gap-y-16 lg:mx-0 lg:max-w-none lg:grid-cols-2">
         <!-- Desktop image (alternately on the left and on the right) -->
         <div class="hidden lg:block lg:pr-4 <?= $index % 2 ? 'lg:order-last' : '' ?>">
            <div class="relative mx-auto aspect-square w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl"><?= $logo ?></div>
         </div>
         <div class="text-base/7 text-gray-700 lg:max-w-lg">
            <h2 class="text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl"><?= esc($title) ?></h2>
            <?php if (!empty($subtitle)): ?>
               <p class="mt-2 text-lg font-medium text-indigo-600"><?= esc($subtitle) ?></p>
            <?php endif; ?>
            <!-- Mobile image -->
            <div class="relative mx-auto my-6 aspect-square w-48 overflow-hidden rounded-3xl bg-white shadow-xl sm:w-56 lg:hidden"><?= $logo ?></div>
            <div class="max-w-xl">
               <?php foreach ($paragraphs as $paragraph): ?>
                  <p class="mt-6"><?= $paragraph ?></p>
               <?php endforeach; ?>
            </div>
         </div>
      </div>
   </div>
</div>
