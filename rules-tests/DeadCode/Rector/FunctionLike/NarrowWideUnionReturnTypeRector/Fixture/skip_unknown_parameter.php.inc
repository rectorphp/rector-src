<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Fixture;

final class skip_unknown_parameter
{
    public function foo($a): string|int|float
    {
        return $a;
    }

    public function bar($a): string|int|null
    {
        if (rand(0, 1)) {
            return 'something';
        }

        return $a;
    }
}

?>
