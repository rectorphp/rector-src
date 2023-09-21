<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class KeepDocblockOnReturn3
{
    public function run()
    {
        function() {
            /**
             * comment
             */
            // something
            // @psalm-suppress UndefinedFunction
            return ff();
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class KeepDocblockOnReturn3
{
    public function run()
    {
        fn() =>
            /**
             * comment
             */
            // something
            // @psalm-suppress UndefinedFunction
            ff();
    }
}

?>
