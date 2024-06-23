<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachToInArrayRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Foreach_\ForeachToInArrayRector\Source\Collection;

final class SkipMethodCalls
{
    public function foreachToInArray($items): bool
    {
        foreach ($items as $item) {
            if ($item == SkipMethodCalls::strtoupper($item)) {
                return true;
            }
        }

        return false;
    }

    public static function strtoupper($item): bool
    {
        return strtoupper((string) $item);
    }
}
