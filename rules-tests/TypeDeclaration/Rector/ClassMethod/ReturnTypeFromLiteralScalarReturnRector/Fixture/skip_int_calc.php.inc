<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class SkipIntCalc
{
    public function run()
    {
        return 3 + 9;
    }
}
