<?php
/**
 * Centred card of the account pages (forgotten password, new password...). Props: title, text (optional, html), slot.
 */
?>
<section class="flex justify-center px-4 py-12 sm:py-20">
   <div class="w-full max-w-md rounded-lg bg-white p-8 shadow-xl ring-1 ring-gray-200">
      <h1 class="text-2xl font-bold leading-tight text-gray-900"><?= esc($title) ?></h1>
      <?php if (!empty($text)): ?>
         <p class="mt-2 text-sm text-gray-600"><?= $text ?></p>
      <?php endif; ?>
      <?= $slot ?>
   </div>
</section>
