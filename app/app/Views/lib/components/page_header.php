<?php
/**
 * Title of an administration page. Props:
 *  - title, subtitle (optional, escaped) or subtitle_html (optional, html: Alpine bindings...)
 *  - back: [url, label] of a link above the title (optional)
 *  - slot: actions on the right (buttons)
 */
?>
<div class="<?= classes('sm:flex justify-start sm:items-center mt-12 mb-8 border-b py-4', $class) ?>">
   <div class="sm:flex-auto">
      <?php if (!empty($back)): ?>
         <a href="<?= $back[0] ?>" class="text-sm font-medium text-gray-500 hover:text-gray-700">&larr; <?= esc($back[1]) ?></a>
      <?php endif; ?>
      <h1 class="<?= classes('font-semibold text-4xl leading-tight text-gray-900', !empty($back) ? 'mt-2' : null) ?>"><?= esc($title) ?></h1>
      <?php if (!empty($subtitle) || !empty($subtitle_html)): ?>
         <p class="mt-1 text-sm text-gray-500"><?= !empty($subtitle_html) ? $subtitle_html : esc($subtitle) ?></p>
      <?php endif; ?>
   </div>
   <?php if ($slot !== ''): ?>
      <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none"><?= $slot ?></div>
   <?php endif; ?>
</div>
