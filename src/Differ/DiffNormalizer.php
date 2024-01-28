<?php

declare(strict_types=1);

namespace Rector\Differ;

final class DiffNormalizer
{
    public static function normalize(string $diff): string
    {
        return str_replace("\r\n", "\n", $diff);
    }
}
