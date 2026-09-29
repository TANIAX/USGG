<?php
/**
 * Checkbox with its text. Props:
 *  - label (escaped), description (escaped, card only), note (small coloured text under the description, card only)
 *  - name, value (default "1"), checked
 *  - variant: inline (default), card (bordered box with the description) or panel (grey box with the description)
 *  - locked: can not be changed (the value is still sent)
 *  - attrs: attributes of the <input> (x-model, @change...)
 */
$variant = $variant ?? 'inline';
$card = $variant !== 'inline';
$locked = $locked ?? false;
$input = attrs(['type' => 'checkbox', 'name' => $name ?? null, 'value' => $value ?? '1', 'checked' => !empty($checked)]
   + ($locked ? ['onclick' => 'return false', 'aria-disabled' => 'true'] : [])
   + ['class' => classes('h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600', $card ? 'mt-0.5' : null)] + $attrs);
?>
<?php if ($card): ?>
   <label class="<?= $variant === 'panel'
      ? classes('flex items-start gap-x-3 rounded-lg bg-gray-50 p-4', $locked ? null : 'cursor-pointer', $class)
      : classes('flex items-start gap-x-3 rounded-lg p-3 ring-1 ring-inset ring-gray-200', $locked ? null : 'cursor-pointer hover:bg-gray-50', $class) ?>">
      <input<?= $input ?>>
      <span>
         <span class="block text-sm font-medium text-gray-900"><?= esc($label ?? '') ?></span>
         <?php if (!empty($description)): ?><span class="block text-sm text-gray-500"><?= esc($description) ?></span><?php endif; ?>
         <?php if (!empty($note)): ?><span class="mt-1 block text-xs text-amber-700"><?= esc($note) ?></span><?php endif; ?>
      </span>
   </label>
<?php else: ?>
   <label class="<?= classes('inline-flex items-center gap-x-2 text-sm text-gray-700', $class) ?>">
      <input<?= $input ?>>
      <?= $slot !== '' ? $slot : esc($label ?? '') ?>
   </label>
<?php endif; ?>
