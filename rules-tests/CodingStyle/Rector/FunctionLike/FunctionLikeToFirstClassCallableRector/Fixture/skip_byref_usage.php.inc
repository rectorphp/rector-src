<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

function (&$foo)
{
    return Foo::foo($foo);
};

?>
