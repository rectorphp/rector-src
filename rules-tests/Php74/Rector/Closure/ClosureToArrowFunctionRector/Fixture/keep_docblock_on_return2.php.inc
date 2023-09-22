<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;
class KeepDocblockOnReturn2
{
    public function run()
    {
        function() {
            // @psalm-suppress UndefinedFunction
            return ff();
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;
class KeepDocblockOnReturn2
{
    public function run()
    {
        fn() =>
            // @psalm-suppress UndefinedFunction
            ff();
    }
}

?>
