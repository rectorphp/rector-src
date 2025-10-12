<?php

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector\Fixture;

function wrapToPreventPhpStanCallingMethods ()
{
    $a = SomeClass::class;

    SomeClass::class;

    bar()::FOO;
}
?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector\Fixture;

function wrapToPreventPhpStanCallingMethods ()
{
    $a = SomeClass::class;

    bar();
}
?>
