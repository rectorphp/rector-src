<?php

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector\Fixture;

function wrapToPreventPhpStanCallingMethods ()
{
    $a = SomeClass::$a;

    SomeClass::$a;

    bar()::$a;

    bar()::${baz()}::${foo()};
}
?>
