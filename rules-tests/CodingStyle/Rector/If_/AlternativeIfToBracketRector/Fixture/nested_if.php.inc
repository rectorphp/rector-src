<?php

namespace Rector\Tests\CodingStyle\Rector\If_\AlternativeIfToBracketRector\Fixture;

function nestedIf($a, $b)
{
    if ($a) :
        $x = 1;
    else :
        if ($b) :
            $x = 2;
        endif;
    endif;

    return $x;
}
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\If_\AlternativeIfToBracketRector\Fixture;

function nestedIf($a, $b)
{
    if ($a) {
        $x = 1;
    } else if ($b) {
        $x = 2;
    }

    return $x;
}
