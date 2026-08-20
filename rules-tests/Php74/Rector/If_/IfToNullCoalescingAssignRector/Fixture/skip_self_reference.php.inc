<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

function skipSelfReference($value)
{
    if (null === $value) {
        $value = $value . 'suffix';
    }
}
