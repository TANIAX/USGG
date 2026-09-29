<?php
/**
 * Filter pill (button, or link when "href" is given). Props:
 *  - label (escaped) or the slot (html); for a text computed by Alpine: 'attrs' => ['x-text' => '...']
 *  - active: selected (rendered by the server), or active_alpine: javascript expression of the selection
 *  - tone: indigo (default) or dark (colour when selected)
 */
$tone = $tone ?? 'indigo';
$on = $tone === 'dark' ? 'bg-gray-900 text-white ring-gray-900' : 'bg-indigo-600 text-white ring-indigo-600';
$off = 'bg-white text-gray-700 ring-gray-300 hover:bg-gray-50';

$root = ['class' => classes('rounded-full px-3 py-1 text-sm font-medium ring-1 ring-inset', isset($active_alpine) ? null : (!empty($active) ? $on : $off), $class)];
if (isset($active_alpine))
   $root[':class'] = "($active_alpine) ? '$on' : '$off'";
if (isset($href))
   $root = ['href' => $href] + $root + (!empty($active) ? ['aria-current' => 'page'] : []);
else
   $root = ['type' => 'button'] + $root + (isset($active_alpine) ? [':aria-pressed' => "($active_alpine).toString()"] : []);
$tag = isset($href) ? 'a' : 'button';
?>
<<?= $tag ?><?= attrs($root + $attrs) ?>><?= $slot !== '' ? $slot : esc($label ?? '') ?></<?= $tag ?>>
