<?php

namespace Rector\Tests\CodeQuality\Rector\Stmt\DeadCodeRemovingRector\Fixture\PropertyFetch;

function wrapToPreventPhpStanCallingMethods ()
{
    $var->prop = 1;

    $var->prop;

    //comment
    $var->{methodCall()};

    //comment
    ${methodCall()}->prop;

    //comment
    ${methodCall1()}->{methodCall2()};
}
?>
