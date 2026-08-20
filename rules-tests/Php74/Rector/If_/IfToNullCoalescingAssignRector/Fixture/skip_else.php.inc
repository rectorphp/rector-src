<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

function skipElse($value)
{
    if (null === $value) {
        $value = 'default';
    } else {
        $value = 'other';
    }
}
