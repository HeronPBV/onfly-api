<?php

use Carbon\Carbon;

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
