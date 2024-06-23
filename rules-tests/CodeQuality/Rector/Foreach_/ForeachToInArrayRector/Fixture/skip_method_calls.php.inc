<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachToInArrayRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Foreach_\ForeachToInArrayRector\Source\Collection;

final class SkipMethodCalls
{
    public function foreachToInArray($items): bool
    {
        foreach ($items as $item) {
            if ($item == $this->strtoupper($item)) {
                return true;
            }
        }

        return false;
    }

    public function strtoupper($item): bool
    {
        return strtoupper((string) $item);
    }
}
