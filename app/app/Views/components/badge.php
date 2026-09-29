<?php
/**
 * Small label. Props:
 *  - label (escaped) or the slot (html); for a text computed by Alpine: 'attrs' => ['x-text' => '...']
 *  - color: gray (default), gray-dark, indigo, purple, green, amber, red, outline, overlay, none (colours given by the caller)
 *  - ring: thin border of the colour
 *  - shape: pill (default, rounded) or tag (square corners)
 *  - dot: colour of a dot before the text (css colour); dot_alpine: same, computed by Alpine
 */
$color = $color ?? 'gray';
$shape = $shape ?? 'pill';
$ring = $ring ?? false;

$colors = [
   'gray' => 'bg-gray-100 text-gray-600',
   'gray-dark' => 'bg-gray-200 text-gray-600',
   'indigo' => 'bg-indigo-50 text-indigo-700',
   'purple' => 'bg-purple-50 text-purple-700',
   'green' => 'bg-green-50 text-green-700',
   'amber' => 'bg-amber-50 text-amber-800',
   'red' => 'bg-red-50 text-red-700',
   'outline' => 'text-gray-700',
   'overlay' => 'bg-black/60 text-white',
   'none' => '',
];
$rings = [
   'indigo' => 'ring-1 ring-inset ring-indigo-200',
   'purple' => 'ring-1 ring-inset ring-purple-200',
   'green' => 'ring-1 ring-inset ring-green-600/20',
   'amber' => 'ring-1 ring-inset ring-amber-600/20',
   'red' => 'ring-1 ring-inset ring-red-600/10',
   'outline' => 'ring-1 ring-inset ring-gray-200',
];

$classes = classes(
   'inline-flex items-center gap-x-1.5 text-xs font-medium',
   $shape === 'pill' ? 'rounded-full px-2 py-0.5' : 'rounded px-1.5 py-0.5',
   $colors[$color],
   $ring || $color === 'outline' ? ($rings[$color] ?? '') : null,
   $class
);
?>
<span<?= attrs(['class' => $classes] + $attrs) ?>><?php if (isset($dot)): ?><span class="h-1.5 w-1.5 flex-none rounded-full" style="background-color: <?= esc($dot, 'attr') ?>"></span><?php elseif (isset($dot_alpine)): ?><span class="h-1.5 w-1.5 flex-none rounded-full" :style="`background-color: ${<?= esc($dot_alpine, 'attr') ?>}`"></span><?php endif; ?><?= $slot !== '' ? $slot : esc($label ?? '') ?></span>
