<?php

namespace App\Shared\Utils;

class StringUtils
{
    public static function toSnakeCase(string $input): string
    {
        return ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $input)), '_');
    }

    public static function getSuffix(string $input, string $separator): string
    {
        $suffix = strrchr($input, $separator);
        return $suffix ? substr($suffix, 1) : $input;
    }
}