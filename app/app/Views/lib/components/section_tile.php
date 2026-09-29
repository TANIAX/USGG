<?php
/**
 * Tile of a section on the home page: logo, ages and name, link to its presentation. Props:
 *  - section: ['name', 'ages', 'logo', 'href'] (UnitHelper::sections())
 *  - mirrored: logo on the right from "md" (left column of the home page)
 */
$mirrored = $mirrored ?? false;
?>
<div class="<?= $mirrored ? 'flex justify-center md:justify-normal md:flex-row-reverse w-full py-8' : 'flex justify-center md:justify-normal flex-row-reverse md:flex-row w-full py-8' ?>">
   <div class="bg-white p-4 w-32 h-40 flex items-center justify-center rounded-lg shadow-md transition-all ease-in-out magnetic hover:w-40">
      <img src="<?= base_url($section['logo']) ?>" class="<?= $mirrored ? 'w-28 h-28 bg-white object-contain origin-bottom rotate-4 shadow-md' : 'w-28 h-28 bg-white object-contain origin-bottom -rotate-4 shadow-md' ?>" alt="Logo des <?= esc($section['name'], 'attr') ?>">
   </div>
   <a href="<?= $section['href'] ?>" class="flex flex-col items-center justify-center p-8 magnetic">
      <span class="font-bold text-4xl tracking-tighter uppercase leading-tight hover:text-indigo-600"><?= esc($section['ages']) ?></span>
      <span class="font-bold text-gray-500 text-2xl tracking-tight italic uppercase"><?= esc($section['name']) ?></span>
   </a>
</div>
