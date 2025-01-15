<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FuncLikeToFirstClassCallableRector\Fixture;

function (&$foo)
{
    return Foo::foo($foo);
};

?>
