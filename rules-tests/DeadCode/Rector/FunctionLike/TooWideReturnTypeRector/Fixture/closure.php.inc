<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\RemoveUnusedReturnTypeRector\Fixture;

$callback = function(): string|int|array|null {
    if (rand(0, 1)) {
        return 'result';
    }

    if (rand(0, 1)) {
        return 42;
    }

    return null;
};

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\RemoveUnusedReturnTypeRector\Fixture;

$callback = function(): string|int|null {
    if (rand(0, 1)) {
        return 'result';
    }

    if (rand(0, 1)) {
        return 42;
    }

    return null;
};

?>
