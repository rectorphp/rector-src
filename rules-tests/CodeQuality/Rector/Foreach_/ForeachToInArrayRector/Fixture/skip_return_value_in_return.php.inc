<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachToInArrayRector\Fixture;

final class SkipReturnValueInReturn
{
    public function run($value)
    {
        foreach ($this->items as $item) {
            if (100 === $item) {
                return true;
            }
        }

        return $value;
    }
}
