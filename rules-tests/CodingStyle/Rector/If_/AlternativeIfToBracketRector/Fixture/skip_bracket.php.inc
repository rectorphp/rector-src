<?php

namespace Rector\Tests\CodingStyle\Rector\If_\AlternativeIfToBracketRector\Fixture;

function skipBracket($a, $b)
{
    if ($a) {
        $x = 1;
    } elseif ($b) {
        $x = 2;
    } else {
        $x = 3;
    }

    return $x;
}
