<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter4.github.io/CodeIgniter4/
 */

if (!function_exists('asset_url')) {
    /**
     * Url of a file of public/ (CSS, JS...) with its version (date of modification): the browsers load
     * the new file as soon as it changes instead of keeping an old one in their cache.
     *
     * @param string $path path in public/, e.g. "assets/js/script.js"
     */
    function asset_url(string $path): string
    {
        $file = FCPATH . ltrim($path, '/');
        return base_url($path) . (is_file($file) ? '?v=' . filemtime($file) : '');
    }
}
