<?php

namespace Rector\Tests\Php70\Rector\FuncCall\RandomFunctionRector\Fixture;

function skipMtSrandSeeded()
{
    mt_srand(42);

    $a = rand(1, 6);
}

?>
