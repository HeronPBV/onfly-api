<?php

use Carbon\Carbon;

define('DOC', 'https://github.com/HeronPBV/onfly-api');

if (!function_exists('formatDate')) {
    function formatDate(string $date): string
    {
        return Carbon::parse($date)->format('d/m/Y');
    }
}

if (!function_exists('formatValue')) {
    function formatValue(float $value): string
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }
}
