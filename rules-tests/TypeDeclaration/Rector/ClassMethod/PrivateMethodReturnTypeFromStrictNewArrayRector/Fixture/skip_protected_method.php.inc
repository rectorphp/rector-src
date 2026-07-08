<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\PrivateMethodReturnTypeFromStrictNewArrayRector\Fixture;

final class SkipProtectedMethod
{
    protected function run()
    {
        $values = [];

        return $values;
    }
}
