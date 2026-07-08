<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ShortenElseIfRector\Fixture;

function skipAlternativeSyntax($n, $c, $x, $e, $d)
{
    if ($n == $c) :
        $a = 1;
    else :
        if ($x || $n <= $e) :
            $a = 2;
        elseif ($d && ! $x) :
            $a = 3;
        endif;
    endif;

    return $a;
}
