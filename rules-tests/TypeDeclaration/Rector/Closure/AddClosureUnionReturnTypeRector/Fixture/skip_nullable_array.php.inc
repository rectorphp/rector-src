<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureUnionReturnTypeRector\Fixture;

final class SkipNullableArray
{
    public function run()
    {
        function () {
            if (rand(0, 1)) {
                return [];
            }

            return null;
        };
    }
}
