<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class KeepDocblockOnReturn4
{
    public function run()
    {
        function deep() {
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
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class KeepDocblockOnReturn4
{
    public function run()
    {
        function deep() {
            fn() =>
                /**
                 * comment
                 */
                // something
                // @psalm-suppress UndefinedFunction
                ff();
        }
    }
}

?>
