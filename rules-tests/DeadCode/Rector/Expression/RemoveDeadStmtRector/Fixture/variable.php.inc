<?php

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector\Fixture;

function wrapToPreventPhpStanCallingMethods()
{
    $a;

    //keep comment
    ${methodCall()};
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector\Fixture;

function wrapToPreventPhpStanCallingMethods()
{
    //keep comment
    methodCall();
}

?>
