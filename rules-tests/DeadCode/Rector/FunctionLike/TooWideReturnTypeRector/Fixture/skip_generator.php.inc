<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\RemoveUnusedReturnTypeRector\Fixture;

class SkipGenerator
{
    public function generate(): \Generator|array|null
    {
        if (rand(0, 1)) {
            yield 1;
            yield 2;
        }

        return null;
    }

    public function delegate(): \Generator|array
    {
        yield from [1, 2, 3];
    }
}

?>
