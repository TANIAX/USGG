<?php
/**
 * Tag of a section (name and dot of its colour). Props:
 *  - section: section (object or array with "name" and "color"), rendered by the server
 *  - alpine: javascript expression of the section, rendered by Alpine (in a x-for...)
 */
if (isset($alpine)) {
   echo component('badge', ['color' => 'outline', 'dot_alpine' => $alpine . '.color', 'slot' => '<span x-text="' . esc($alpine, 'attr') . '.name"></span>', 'class' => $class, 'attrs' => $attrs]);
} else {
   $section = (object) $section;
   echo component('badge', ['color' => 'outline', 'dot' => $section->color, 'label' => $section->name, 'class' => $class, 'attrs' => $attrs]);
}
