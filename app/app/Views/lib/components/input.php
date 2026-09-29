<?php
/**
 * Form control alone (see components/field for the label and the help). Props:
 *  - type: text (default), email, url, tel, password, number, date, time, textarea, select
 *  - name, id (default: the name; false: no id), value (raw value, escaped here), placeholder, required
 *  - options: choices of a select [value => label], or groups [group label => [value => label]]; placeholder: first empty choice ("Choisir...")
 *  - rows: rows of a textarea
 *  - width: width class (default "w-full")
 *  - attrs: other attributes (x-model, maxlength, autocomplete, :disabled...)
 */
$type = $type ?? 'text';
$name = $name ?? null;
$id = ($id ?? null) === false ? null : ($id ?? $name);
$value = $value ?? null;
$base = classes('block rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 disabled:bg-gray-50 disabled:text-gray-500 sm:text-sm sm:leading-6', $width ?? 'w-full', $class);
$common = ['name' => $name, 'id' => $id, 'class' => $base, 'required' => !empty($required)];
?>
<?php if ($type === 'textarea'): ?>
<textarea<?= attrs($common + ['rows' => $rows ?? 4, 'placeholder' => $placeholder ?? null] + $attrs) ?>><?= esc((string) $value) ?></textarea>
<?php elseif ($type === 'select'): ?>
<select<?= attrs($common + $attrs) ?>>
   <?php if (isset($placeholder)): ?><option value=""><?= esc($placeholder) ?></option><?php endif; ?>
   <?php foreach ($options ?? [] as $optionValue => $optionLabel): ?>
      <?php if (is_array($optionLabel)): ?>
         <optgroup label="<?= esc((string) $optionValue, 'attr') ?>">
            <?php foreach ($optionLabel as $groupValue => $groupLabel): ?>
               <option value="<?= esc((string) $groupValue, 'attr') ?>"<?= $value !== null && (string) $value === (string) $groupValue ? ' selected' : '' ?>><?= esc($groupLabel) ?></option>
            <?php endforeach; ?>
         </optgroup>
      <?php else: ?>
         <option value="<?= esc((string) $optionValue, 'attr') ?>"<?= $value !== null && (string) $value === (string) $optionValue ? ' selected' : '' ?>><?= esc($optionLabel) ?></option>
      <?php endif; ?>
   <?php endforeach; ?>
</select>
<?php else: ?>
<input<?= attrs(['type' => $type] + $common + ['value' => $value, 'placeholder' => $placeholder ?? null] + $attrs) ?>>
<?php endif; ?>
