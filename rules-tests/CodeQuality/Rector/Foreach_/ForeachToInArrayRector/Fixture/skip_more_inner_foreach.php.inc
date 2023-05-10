<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachToInArrayRector\Fixture;

final class SkipMoreInnerForeach
{
    public function run($value)
    {
        foreach ($this->items as $item) {
            $value = 100;

            if (100 === $item) {
                return true;
            }
        }

        return $value;
    }
}
