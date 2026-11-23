<?php

namespace Rector\Tests\Php70\Rector\FuncCall\RandomFunctionRector\Fixture;

function seededKeepsRand()
{
    srand(42);

    $a = rand(1, 6);
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\FuncCall\RandomFunctionRector\Fixture;

function seededKeepsRand()
{
    mt_srand(42);

    $a = rand(1, 6);
}

?>
