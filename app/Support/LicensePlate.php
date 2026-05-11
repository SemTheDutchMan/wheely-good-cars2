<?php

namespace App\Support;

final class LicensePlate
{
    public const INPUT_PATTERN = '/^\\d{2}-[A-Z]{2,3}-\\d{2,3}$/i';

    public static function canonicalize(string $value): string
    {
        $normalized = strtoupper(trim($value));

        return preg_match(self::INPUT_PATTERN, $normalized) === 1 ? $normalized : '';
    }

    public static function normalize(string $value): string
    {
        $canonical = self::canonicalize($value);

        return $canonical === '' ? '' : str_replace('-', '', $canonical);
    }
}
