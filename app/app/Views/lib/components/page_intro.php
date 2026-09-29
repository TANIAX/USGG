<?php
/**
 * Title and introduction of a page, the text on two columns. Props:
 *  - title
 *  - columns: two lists of paragraphs (html written in the views: links...)
 */
?>
<h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl"><?= esc($title) ?></h1>
<div class="<?= classes('mt-8 grid gap-8 text-lg text-gray-600 lg:grid-cols-2 lg:gap-12', $class) ?>">
   <?php foreach ($columns as $paragraphs): ?>
      <div>
         <?php foreach ($paragraphs as $i => $paragraph): ?>
            <p<?= $i ? ' class="mt-4"' : '' ?>><?= $paragraph ?></p>
         <?php endforeach; ?>
      </div>
   <?php endforeach; ?>
</div>
