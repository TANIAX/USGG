<?php
/**
 * Button showing only an icon. Props:
 *  - icon: name of an icon of components/icon; icon_class: size of the icon (default "h-5 w-5")
 *  - label: text read by screen readers (and tooltip)
 *  - class: classes of the button (default: small grey button)
 *  - attrs: other attributes (@click, :disabled...)
 */
?>
<button<?= attrs(['type' => 'button', 'title' => $label, 'class' => $class ?: 'rounded p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-700 disabled:opacity-30'] + $attrs) ?>>
   <span class="sr-only"><?= esc($label) ?></span>
   <?= component('icon', ['name' => $icon, 'class' => $icon_class ?? 'h-5 w-5']) ?>
</button>
