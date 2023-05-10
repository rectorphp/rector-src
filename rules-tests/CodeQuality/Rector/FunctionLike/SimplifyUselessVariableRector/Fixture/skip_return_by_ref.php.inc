<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector\Fixture;

function & notAnonymous()
{
    for ($i = 0; $i < 4; $i++) {
        $var = 42;
        return $var;
    }
}
