<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

function SkipClosureUseByReference()
{
    $isChanged = false;
    (function ($isChanged) {
        if ($isChanged) {
            return 1;
        }

        return 0;
    })(true);

    return $isChanged;
}
