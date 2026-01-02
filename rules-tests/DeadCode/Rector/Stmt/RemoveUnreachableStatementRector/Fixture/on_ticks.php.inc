<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

declare(ticks=1) {
    echo 'test';

    die;

    echo 'unreachable';
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

declare(ticks=1) {
    echo 'test';

    die;
}

?>
