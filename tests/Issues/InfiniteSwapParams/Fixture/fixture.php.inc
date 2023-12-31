<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\InfiniteSwapParams\Fixture;

class Fixture
{
    function foo($a = 1, $b) { return 3; }
    function bar() { return self::foo(1, 2); }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\InfiniteSwapParams\Fixture;

class Fixture
{
    function foo($b, $a = 1) { return 3; }
    function bar() { return self::foo(2, 1); }
}

?>
