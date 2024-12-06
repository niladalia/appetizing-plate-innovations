<?php

namespace App\Shared\Utils;

use Generator;

class ArrayUtils
{
    public static function chunk(array $input, int $batchSize): Generator
    {
        $batch = [];
        foreach ($input as $item) {
            $batch[] = $item;
            if (count($batch) >= $batchSize) {
                yield $batch;
                $batch = [];
            }
        }
        if ($batch) {
            yield $batch;
        }
    }
}