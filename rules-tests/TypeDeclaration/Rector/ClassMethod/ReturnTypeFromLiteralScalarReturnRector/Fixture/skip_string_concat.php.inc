<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromLiteralScalarReturnRector\Fixture;

final class SkipStringConcat
{
    public function run()
    {
        return 'hello' . 'world';
    }
}
