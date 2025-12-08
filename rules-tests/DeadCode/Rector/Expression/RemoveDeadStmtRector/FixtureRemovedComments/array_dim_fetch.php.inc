<?php

namespace Rector\Tests\CodeQuality\Rector\Stmt\DeadCodeRemovingRector\Fixture\ArrayDimFetch;

function wrapToPreventPhpStanCallingMethods()
{
    $var[0] = 1;

    $var[0];

    //comment 1
    $var[methodCall()];

    //comment 2
    ${methodCall()}[0];

    //comment 3
    ${methodCall1()}[methodCall2()];
}
?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Stmt\DeadCodeRemovingRector\Fixture\ArrayDimFetch;

function wrapToPreventPhpStanCallingMethods()
{
    $var[0] = 1;

    //comment 1
    methodCall();

    //comment 2
    methodCall();
    methodCall2();

    //comment 3
    methodCall1();
}
?>
