<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureNeverReturnTypeRector\Fixture;

use Exception;

final class SkipHasReturn
{
    public function run()
    {
        function () {
            if (rand(0, 1)) {
                throw new Exception('test');
            }

            return 1;
        };
    }
}
