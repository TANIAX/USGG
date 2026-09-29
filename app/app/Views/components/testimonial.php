<?php
/**
 * Testimonial: quote, photo, name and totem. Props: quote, name, totem, picture (url), class (animation "reveal"...), attrs.
 */
?>
<figure<?= attrs(['class' => classes('rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5', $class)] + $attrs) ?>>
   <blockquote class="text-gray-900">
      <p>“<?= esc($quote) ?>”</p>
   </blockquote>
   <figcaption class="mt-6 flex items-center gap-x-4">
      <img class="h-10 w-10 rounded-full bg-gray-50" src="<?= esc($picture, 'attr') ?>" alt="">
      <div>
         <div class="font-semibold"><?= esc($name) ?></div>
         <div class="text-gray-600">@<?= esc($totem) ?></div>
      </div>
   </figcaption>
</figure>
