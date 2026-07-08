<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\PrivateMethodReturnTypeFromStrictNewArrayRector\Fixture;

final class SkipPublicMethod
{
    public function run()
    {
        $values = [];

        return $values;
    }
}
