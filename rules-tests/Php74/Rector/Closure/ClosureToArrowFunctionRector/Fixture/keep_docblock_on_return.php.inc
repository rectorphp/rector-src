<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class KeepDocblockOnReturn
{
    public function run()
    {
        function() {
            /** @psalm-suppress UndefinedFunction */
            return ff();
        };

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

class KeepDocblockOnReturn
{
    public function run()
    {
        
            /** @psalm-suppress UndefinedFunction */

            fn() => ff();

        
            // @psalm-suppress UndefinedFunction

            fn() => ff();
    }
}

?>
