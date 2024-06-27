<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureReturnTypeFromReturnCastRector\Fixture;

final class SkipNoCastReturn
{
    public function run()
    {
        function ($param) {
            return $param;
        };
    }
}
