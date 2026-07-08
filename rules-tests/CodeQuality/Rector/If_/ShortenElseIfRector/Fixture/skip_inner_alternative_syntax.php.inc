<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ShortenElseIfRector\Fixture;

function skipInnerAlternativeSyntax($cond1, $cond2)
{
    if ($cond1) {
        $a = 1;
    } else {
        if ($cond2) :
            $a = 2;
        endif;
    }

    return $a;
}
