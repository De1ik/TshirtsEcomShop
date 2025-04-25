<?php

if (!function_exists('extractColor')) {
    function extractColor($filename, $colors)
    {
        foreach ($colors as $color) {
            if (stripos($filename, $color) !== false) {
                return $color;
            }
        }
        return 'unknown';
    }
}
