<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector\Fixture {
    function trim()
    {}

    \Closure::fromCallable('Rector\Tests\CodingStyle\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector\Fixture\trim');
    \Closure::fromCallable('trim');
}

namespace {
    \Closure::fromCallable('trim');
    \Closure::fromCallable('\trim');
    \Closure::fromCallable('Psl\Str\trim');
    \Closure::fromCallable('\Psl\Str\trim');
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector\Fixture {
    function trim()
    {}

    \Rector\Tests\CodingStyle\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector\Fixture\trim(...);
    \trim(...);
}

namespace {
    \trim(...);
    \trim(...);
    \Psl\Str\trim(...);
    \Psl\Str\trim(...);
}

?>
