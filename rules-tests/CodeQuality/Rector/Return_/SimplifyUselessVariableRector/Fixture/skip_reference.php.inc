<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyUselessVariableRector\Fixture;

function SomeReference()
{
    $isChanged = false;
    function ($x) use (&$isChanged) {
        $isChanged = (bool) rand(0, 1);
        return $isChanged;
    };

    if ($isChanged) {
        return true;
    }

    return false;
}