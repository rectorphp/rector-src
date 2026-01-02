<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

declare(ticks=1) {
    echo 'test';

    throw new \Exception();

    echo 'unreachable';
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

declare(ticks=1) {
    echo 'test';

    throw new \Exception();
}

?>
