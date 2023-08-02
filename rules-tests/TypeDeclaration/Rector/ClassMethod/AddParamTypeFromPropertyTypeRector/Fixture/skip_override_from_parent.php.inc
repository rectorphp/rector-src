<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Source\ParentWithNumber;

final class SkipOverrideFromParent extends ParentWithNumber
{
    protected function getNumber($number)
    {
        $this->number = $number;
    }
}
