<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

final class SkipNoCastReturn
{
    public function run()
    {
        function ($param) {
            return $param;
        };
    }
}
