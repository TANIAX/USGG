<?php
/**
 * Step of a vertical timeline (registration). Props:
 *  - number (text of the dot: 1, 2, 4A...), title, period (optional, on the right of the title)
 *  - index: position (alternates the colours)
 *  - slot: content of the step (html)
 */
$tint = ($index ?? 0) % 2 ? 'bg-sky-50' : 'bg-slate-100';
?>
<div class="ml-10 relative flex flex-col items-center mb-28 rounded space-y-4 transform transition hover:-translate-y-2 md:space-y-0">
   <div class="lg:text-2xl font-bold text-black uppercase flex flex-col lg:flex-row justify-between lg:items-center w-full px-4">
      <h2 class="text-gray-800"><?= esc($title) ?></h2>
      <?php if (!empty($period)): ?><span class="text-base text-gray-500"><?= esc($period) ?></span><?php endif; ?>
   </div>
   <!-- Dot on the vertical line, and line joining it to the box -->
   <div class="<?= $tint ?> w-7 h-7 absolute -left-10 transform -translate-x-2/4 rounded-full z-10 mt-2 flex justify-center items-center border-2 border-white font-semibold top-[85px] md:mt-0"><?= esc($number) ?></div>
   <div class="<?= $tint ?> w-10 h-1 absolute -left-10 z-0 top-24"></div>
   <div class="<?= $tint ?> flex-auto w-full p-4 rounded-lg shadow-lg"><?= $slot ?></div>
</div>
