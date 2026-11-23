<?php

namespace Rector\Tests\Php70\Rector\FuncCall\RandomFunctionRector\Fixture;

function skipUnorderedBounds(int $min, int $max, $a, $b, $d)
{
    rand($min, $max);

    rand($d, rand($a, $b));

    rand($a, \Other\Scope\mt_rand($a));
}

?>
