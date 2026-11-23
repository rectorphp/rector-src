<?php

namespace Rector\Tests\Php70\Rector\FuncCall\RandomFunctionRector\Fixture;

function randomFunction()
{
    rand();

    a(getrandmax());

    a(rand());

    $a = rand(1, 2) + mt_rand(3, 4);
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\FuncCall\RandomFunctionRector\Fixture;

function randomFunction()
{
    random_int(0, mt_getrandmax());

    a(mt_getrandmax());

    a(random_int(0, mt_getrandmax()));

    $a = random_int(1, 2) + mt_rand(3, 4);
}

?>
