<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

final class SkipReturnAllTypes
{
    public function getData(): string|int|float
    {
        if (rand(0, 1)) {
            return 'text';
        }

        if (rand(0, 1)) {
            return 0.42;
        }

        return 1000;
    }
}

?>
