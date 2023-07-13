<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class SkipUnion
{
    public function run(A|B $p)
    {
        return $p;
    }
}

class A {}
class B {}
