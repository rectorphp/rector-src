<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class SkipEmptyReturn
{
    public function run()
    {
        if (false) {
            return;
        }

        return 'test';
    }
}
