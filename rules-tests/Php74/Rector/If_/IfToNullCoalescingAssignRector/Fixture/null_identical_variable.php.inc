<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

function nullIdenticalVariable($value)
{
    if (null === $value) {
        $value = 'default';
    }
}

function variableIdenticalNull($value)
{
    if ($value === null) {
        $value = 'default';
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

function nullIdenticalVariable($value)
{
    $value ??= 'default';
}

function variableIdenticalNull($value)
{
    $value ??= 'default';
}

?>
