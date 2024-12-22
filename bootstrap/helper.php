<?php

declare(strict_types=1);

namespace MkConn\Sfc;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if (!function_exists('humanFilesize')) {
    function humanFilesize(int $bytes, int $decimals = 2): string {
        $units = 'BKMGTP';
        $factor = (int) floor((strlen((string) $bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / 1024 ** $factor) . $units[$factor];
    }
}
