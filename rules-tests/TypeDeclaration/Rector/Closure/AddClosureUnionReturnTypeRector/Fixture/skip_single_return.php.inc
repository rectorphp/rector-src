<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureUnionReturnTypeRector\Fixture;

final class SkipSingleReturn
{
    public function run()
    {
        function () {
            return 'one';
        };
    }
}
