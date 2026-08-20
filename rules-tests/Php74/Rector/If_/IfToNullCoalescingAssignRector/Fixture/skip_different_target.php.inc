<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

function skipDifferentTarget($value, $other)
{
    if (null === $value) {
        $other = 'default';
    }
}
