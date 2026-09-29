<?php
/**
 * Line of information with an icon, in a <dl> (date, place...). Props:
 *  - icon: name of an icon of components/icon; label: name of the information (read by screen readers)
 *  - slot: the information (html); attrs: attributes of the line (x-show...)
 */
?>
<div<?= attrs(['class' => classes('flex items-start gap-x-3', $class)] + $attrs) ?>>
   <dt class="mt-0.5"><span class="sr-only"><?= esc($label) ?></span><?= component('icon', ['name' => $icon, 'class' => 'h-5 w-5 text-gray-400']) ?></dt>
   <dd><?= $slot ?></dd>
</div>
