<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Fixture;

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

    public function implicitReturn(): string|null
    {
        if (rand(0, 1)) {
            return 'something';
        }
    }

    function foo(): int|null
    {
        if (rand(0, 1)) {
             return 1;
        }

        if (rand(0, 1)) {
             return 2;
        }

        if (rand(0, 1)) {
             return 3;
        }

        return null;
    }
}

?>
