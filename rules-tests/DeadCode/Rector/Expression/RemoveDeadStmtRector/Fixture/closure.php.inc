<?php

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector\Fixture;

function wrapToPreventPhpStanCallingMethods ()
{
    $a = function () {};
    function () {};
}
?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector\Fixture;

function wrapToPreventPhpStanCallingMethods ()
{
    $a = function () {};
}
?>
