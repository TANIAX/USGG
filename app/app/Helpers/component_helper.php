<?php

/**
 * Server-side UI components.
 *
 * A component is a view of app/Views/components/ rendered with its own props only (isolated scope: the variables
 * of the page are not visible in the component, and the component variables do not leak into the page).
 *
 *   <?= component('button', ['label' => 'Enregistrer', 'type' => 'submit']) ?>
 *
 * Components with a content ("slot"):
 *
 *   <?php component_open('page_header', ['title' => 'Documents']) ?>
 *      ...html of the slot...
 *   <?= component_close() ?>
 *
 * Every component accepts "class" (classes added to the root element) and "attrs" (other attributes of the root
 * element, Alpine directives included: ['x-show' => 'open', '@click' => 'close()']).
 * The Tailwind classes are written in full in the components (never built by concatenation) so that Tailwind finds them.
 */

if (!function_exists('component')) {
    function component(string $name, array $props = [], ?string $slot = null): string
    {
        $file = APPPATH . 'Views/components/' . $name . '.php';
        if (!is_file($file))
            throw new InvalidArgumentException('Unknown component: ' . $name);

        $props += ['slot' => $slot ?? '', 'class' => '', 'attrs' => []];

        return (static function (string $__file, array $__props): string {
            extract($__props, EXTR_SKIP);
            ob_start();
            include $__file;
            return ob_get_clean();
        })($file, $props);
    }
}

if (!function_exists('component_open')) {
    /**
     * Starts the slot of a component, ended by component_close().
     */
    function component_open(string $name, array $props = []): void
    {
        ComponentStack::$stack[] = [$name, $props];
        ob_start();
    }
}

if (!function_exists('component_close')) {
    function component_close(): string
    {
        if (!ComponentStack::$stack)
            throw new LogicException('component_close() without component_open()');

        $slot = ob_get_clean();
        [$name, $props] = array_pop(ComponentStack::$stack);

        return component($name, $props, $slot);
    }
}

if (!function_exists('attrs')) {
    /**
     * Renders HTML attributes: true -> attribute without value, false / null -> skipped, other values escaped.
     * The names are written by the developer (not escaped), which allows Alpine attributes (@click, :class, x-on:...).
     */
    function attrs(array $attributes): string
    {
        $html = '';
        foreach ($attributes as $name => $value) {
            if ($value === null || $value === false)
                continue;
            $html .= ' ' . $name . ($value === true ? '' : '="' . htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '"');
        }
        return $html;
    }
}

if (!function_exists('classes')) {
    /**
     * Joins classes, skipping the empty ones: classes('a', $condition ? 'b' : null, $extra)
     */
    function classes(?string ...$classes): string
    {
        return trim(implode(' ', array_filter(array_map('trim', array_map('strval', $classes)))));
    }
}

if (!function_exists('js_data')) {
    /**
     * Encodes data for a <script> (x-data initialisation...): no "</script>" nor HTML special characters.
     */
    function js_data($data): string
    {
        return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
    }
}

if (!class_exists('ComponentStack', false)) {
    /**
     * @internal Stack of the components opened with component_open()
     */
    class ComponentStack
    {
        public static array $stack = [];
    }
}
