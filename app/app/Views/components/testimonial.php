<?php
/**
 * Testimonial: quote, photo, name and totem. Props: quote, name, totem (optional), picture (url, optional: initial of the
 * name otherwise), featured (bigger text), class (animation "reveal"...), attrs.
 */
$featured = $featured ?? false;
?>
<figure<?= attrs(['class' => classes('flex flex-col rounded-2xl bg-white shadow-lg ring-1 ring-gray-900/5', $featured ? null : 'p-6', $class)] + $attrs) ?>>
   <blockquote class="<?= $featured ? 'p-6 text-lg font-semibold leading-7 tracking-tight text-gray-900 sm:p-12 sm:text-xl sm:leading-8' : 'flex-auto text-gray-900' ?>">
      <p class="whitespace-pre-line">“<?= esc($quote) ?>”</p>
   </blockquote>
   <figcaption class="<?= $featured ? 'flex items-center gap-x-4 border-t border-gray-900/10 px-6 py-4' : 'mt-6 flex items-center gap-x-4' ?>">
      <?php if (!empty($picture)): ?>
         <img class="h-10 w-10 flex-none rounded-full bg-gray-50 object-cover" src="<?= esc($picture, 'attr') ?>" alt="">
      <?php else: ?>
         <span class="flex h-10 w-10 flex-none items-center justify-center rounded-full bg-indigo-50 font-semibold text-indigo-700" aria-hidden="true"><?= esc(mb_strtoupper(mb_substr($name, 0, 1))) ?></span>
      <?php endif; ?>
      <div>
         <div class="font-semibold"><?= esc($name) ?></div>
         <?php if (!empty($totem)): ?><div class="text-gray-600">@<?= esc($totem) ?></div><?php endif; ?>
      </div>
   </figcaption>
</figure>
