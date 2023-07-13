<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class SkipMagic
{
    public function __get(string $name)
    {
        return $name;
    }
}
