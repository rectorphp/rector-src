<?php

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector\Fixture;

function wrapToPreventPhpStanCallingMethods ($object)
{
    $a = isset($a);
    $b = isset($b, $c, $d);

    isset ($b);

    isset($b[func1()]);
}
?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector\Fixture;

function wrapToPreventPhpStanCallingMethods ($object)
{
    $a = isset($a);
    $b = isset($b, $c, $d);

    func1();
}
?>
