<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Source;

abstract class ParentWithNumber
{
    protected string $number;

    abstract protected function getNumber($number);
}
