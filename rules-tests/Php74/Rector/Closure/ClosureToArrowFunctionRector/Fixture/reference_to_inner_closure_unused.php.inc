<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class ReferenceToInnerClosureUnused
{
    public function run($a)
    {
        $y = function() use (&$a) {
            return function() {
            };
        };

        return $y;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class ReferenceToInnerClosureUnused
{
    public function run($a)
    {
        $y = fn() => function() {
        };

        return $y;
    }
}

?>