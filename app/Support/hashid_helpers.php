<?php

use Vinkla\Hashids\Facades\Hashids;

if (! function_exists('hashid')) {
    /** raw id → hashid string */
    function hashid($id): string
    {
        return Hashids::encode($id);
    }
}

if (! function_exists('idFromHashid')) {
    /** hashid → raw id (না পেলে null) */
    function idFromHashid($hash): ?int
    {
        $decoded = Hashids::decode($hash);
        if (! empty($decoded)) {
            return (int) $decoded[0];
        }
        return ctype_digit((string) $hash) ? (int) $hash : null;
    }
}