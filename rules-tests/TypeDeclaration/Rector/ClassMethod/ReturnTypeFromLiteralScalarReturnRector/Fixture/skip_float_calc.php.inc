<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class SkipFloatCalc
{
    public function run()
    {
        return 3.4 + 9.2;
    }
}
