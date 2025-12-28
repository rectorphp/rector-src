<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class KeepMultiCommentsWithVariableAssignOnReturn
{
    public function run()
    {
        $variable = function() {
            // some comment
            /** @psalm-suppress UndefinedFunction */
            return ff();
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class KeepMultiCommentsWithVariableAssignOnReturn
{
    public function run()
    {
        $variable = 
            // some comment
            /** @psalm-suppress UndefinedFunction */
            (fn() => ff());
    }
}

?>
