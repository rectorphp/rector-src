<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class SkipPrivateMethod
{
    private function run()
    {
        $values = [];

        return $values;
    }
}
