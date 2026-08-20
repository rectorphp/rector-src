<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

function skipMultipleStmts($value)
{
    if (null === $value) {
        $value = 'default';
        echo $value;
    }
}
