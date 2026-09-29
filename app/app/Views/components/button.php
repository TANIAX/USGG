<?php
/**
 * Button, or link styled as a button when "href" is given. Props:
 *  - label (escaped) or the slot (html)
 *  - variant: primary (default), secondary, danger, soft
 *  - size: sm, md (default), lg
 *  - block: block element (full width of its container, add "w-full" in a flex container)
 *  - href: renders a link <a>
 *  - type: type of the <button> (default "button")
 *  - icon: name of an icon of components/icon, shown before the label
 */
$variant = $variant ?? 'primary';
$size = $size ?? 'md';
$block = $block ?? false;
$href = $href ?? null;

$variants = [
   'primary' => 'bg-indigo-600 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-indigo-600',
   'secondary' => 'bg-white text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-indigo-600',
   'danger' => 'bg-red-600 text-white shadow-sm hover:bg-red-500 focus-visible:outline-red-600',
   'soft' => 'text-indigo-600 shadow-sm ring-1 ring-inset ring-indigo-100 hover:bg-indigo-50 focus-visible:outline-indigo-600',
];
$sizes = [
   'sm' => 'px-3 py-2 text-sm',
   'md' => 'px-4 py-2 text-sm',
   'lg' => 'px-4 py-3 text-base',
];

$classes = classes(
   $block ? 'block text-center' : ($href !== null ? 'inline-block text-center' : null),
   !empty($icon) ? 'inline-flex items-center justify-center gap-x-1.5' : null,
   'rounded-md font-semibold focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
   $variants[$variant],
   $sizes[$size],
   $class
);
$content = (!empty($icon) ? component('icon', ['name' => $icon, 'class' => 'h-5 w-5 -ml-0.5']) : '') . ($slot !== '' ? $slot : esc($label ?? ''));
?>
<?php if ($href !== null): ?>
<a<?= attrs(['href' => $href, 'class' => $classes] + $attrs) ?>><?= $content ?></a>
<?php else: ?>
<button<?= attrs(['type' => $type ?? 'button', 'class' => $classes] + $attrs) ?>><?= $content ?></button>
<?php endif; ?>
