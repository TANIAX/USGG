<?php
/**
 * Information box. Props:
 *  - the slot (html) or message (escaped)
 *  - tone: info (default, indigo) or muted (grey, centred: empty list)
 */
$tone = $tone ?? 'info';
?>
<p<?= attrs(['class' => classes($tone === 'muted' ? 'rounded-md bg-gray-50 px-4 py-6 text-center text-sm text-gray-500' : 'rounded-md bg-indigo-50 px-4 py-3 text-sm text-indigo-800', $class)] + $attrs) ?>><?= $slot !== '' ? $slot : esc($message ?? '') ?></p>
