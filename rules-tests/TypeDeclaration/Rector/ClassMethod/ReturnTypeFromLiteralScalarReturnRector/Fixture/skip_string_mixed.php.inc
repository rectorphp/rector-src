<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class SkipStringMixed
{
    public function run()
    {
        if (false) {
            return 'hello' . 'world';
        }

        return 'test';
    }
}
