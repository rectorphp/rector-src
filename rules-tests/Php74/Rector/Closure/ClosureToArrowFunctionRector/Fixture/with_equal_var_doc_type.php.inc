<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class WithEqualVarDocType
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
