<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Fixture;

function processValue(): string|int|float|bool
{
    if (rand(0, 1)) {
        return 'text';
    }

    if (rand(0, 1)) {
        return 123;
    }

    return true;
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Fixture;

function processValue(): string|int|bool
{
    if (rand(0, 1)) {
        return 'text';
    }

    if (rand(0, 1)) {
        return 123;
    }

    return true;
}

?>
