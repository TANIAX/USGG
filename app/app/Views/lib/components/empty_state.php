<?php
/**
 * Message when a list is empty. Props:
 *  - icon: name of an icon of components/icon (default "document")
 *  - title (escaped) or title_alpine (javascript expression of the title), text (optional)
 *  - slot: content under the text (button...)
 */
?>
<div<?= attrs(['class' => classes('py-10 text-center', $class)] + $attrs) ?>>
   <?= component('icon', ['name' => $icon ?? 'document', 'class' => 'mx-auto h-12 w-12 text-gray-400']) ?>
   <h3 class="mt-2 text-sm font-semibold text-gray-900"<?= isset($title_alpine) ? attrs(['x-text' => $title_alpine]) : '' ?>><?= esc($title ?? '') ?></h3>
   <?php if (!empty($text)): ?><p class="mt-1 text-sm text-gray-500"><?= esc($text) ?></p><?php endif; ?>
   <?= $slot ?>
</div>
