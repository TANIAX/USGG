<?php
/**
 * Form field: label, control (components/input) and help. Props:
 *  - label, required (star + required attribute), help (text under the field, html allowed)
 *  - all the props of components/input (type, name, value, placeholder, options, rows, width, attrs...)
 *  - class: classes of the wrapper
 *  - slot: replaces the control (custom control, the label and the help are kept)
 */
$input = ['class' => 'mt-2', 'attrs' => $attrs];
foreach (['type', 'name', 'id', 'value', 'placeholder', 'options', 'rows', 'width', 'required'] as $prop) {
   if (isset($$prop))
      $input[$prop] = $$prop;
}
?>
<div<?= attrs(['class' => $class ?: null]) ?>>
   <?php if (!empty($label)): ?>
      <label<?= attrs(['for' => $slot === '' ? (($id ?? null) ?: ($name ?? null)) : ($for ?? null), 'class' => 'block text-sm font-medium leading-6 text-gray-900']) ?>><?= esc($label) ?><?php if (!empty($required)): ?> <span class="text-red-600">*</span><?php endif; ?></label>
   <?php endif; ?>
   <?= $slot !== '' ? $slot : component('input', $input) ?>
   <?php if (!empty($help)): ?>
      <p class="mt-2 text-sm text-gray-500"><?= $help ?></p>
   <?php endif; ?>
</div>
