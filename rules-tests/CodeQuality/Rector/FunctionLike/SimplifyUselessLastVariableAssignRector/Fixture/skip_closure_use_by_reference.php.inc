<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

function SkipClosureUseByReference()
{
    $isChanged = false;
    (function ($x) use (&$isChanged) {
        $isChanged = true;
        return $isChanged;
    })('x');

    return $isChanged;
}
