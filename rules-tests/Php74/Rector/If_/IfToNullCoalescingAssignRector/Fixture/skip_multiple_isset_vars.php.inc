<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

function skipMultipleIssetVars($first, $second)
{
    if (! isset($first, $second)) {
        $first = 'default';
    }
}
