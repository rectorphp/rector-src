<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\FuncLikeToFirstClassCallableRector\Fixture;

function (...$foo)
{
    return FooBar::foo(...$foo);
};

fn (...$foo) => FooBar::foo(...$foo);

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\FuncLikeToFirstClassCallableRector\Fixture;

FooBar::foo(...);

FooBar::foo(...);

?>
