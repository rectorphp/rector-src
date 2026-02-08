<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class SkipClosureWithVarDocblockGeneric
{
    public function run()
    {
        $fn = function (object $query) {
            /** @var \ArrayObject<int, string> $query */
            return $query->count();
        };
    }
}

?>
