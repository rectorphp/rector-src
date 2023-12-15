<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class SkipDiffering
{
    public function run()
    {
        if (false) {
            return 1;
        }

        return 'test';
    }
}
