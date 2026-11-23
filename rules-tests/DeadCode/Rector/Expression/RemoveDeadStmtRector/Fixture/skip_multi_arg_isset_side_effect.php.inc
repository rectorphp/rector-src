<?php

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector\Fixture;

function skipMultiArgIssetSideEffect ($b, $c, $d)
{
    // func2() runs only when $b[func1()] is set
    isset($b[func1()], $c[func2()], $d[func3()]);
}
?>
