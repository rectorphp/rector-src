<?php

namespace Rector\Tests\CodingStyle\Rector\If_\AlternativeIfToBracketRector\Fixture;

function simpleIf($value)
{
    if ($value) :
        $result = 1;
    endif;

    return $result;
}
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\If_\AlternativeIfToBracketRector\Fixture;

function simpleIf($value)
{
    if ($value) {
        $result = 1;
    }

    return $result;
}
