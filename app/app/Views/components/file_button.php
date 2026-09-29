<?php
/**
 * Button choosing a file (label around a hidden <input type="file">). Props:
 *  - name, accept (default "image/*"), multiple
 *  - label (escaped) or label_alpine (javascript expression of the text)
 *  - attrs: attributes of the <input> (@change...)
 */
?>
<label class="<?= classes('inline-block cursor-pointer rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-indigo-600', $class) ?>">
   <span<?= isset($label_alpine) ? attrs(['x-text' => $label_alpine]) : '' ?>><?= esc($label ?? '') ?></span>
   <input<?= attrs(['type' => 'file', 'name' => $name ?? null, 'accept' => $accept ?? 'image/*', 'multiple' => !empty($multiple), 'class' => 'sr-only'] + $attrs) ?>>
</label>
