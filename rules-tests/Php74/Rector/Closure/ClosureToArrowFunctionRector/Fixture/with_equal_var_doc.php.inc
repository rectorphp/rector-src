<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class WithEqualVarDoc
{
    public function run()
    {
        function (string $var) {
            /** @var string $var */
            return $var;
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class WithEqualVarDoc
{
    public function run()
    {
        fn(string $var) =>
            /** @var string $var */
            $var;
    }
}

?>