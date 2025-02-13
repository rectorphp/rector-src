<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

function (...$foo)
{
    return FooBar::foo(...$foo);
};

fn (...$foo) => FooBar::foo(...$foo);

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

FooBar::foo(...);

FooBar::foo(...);

?>
