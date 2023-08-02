<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Source;

class ParentWithNumber
{
    protected string $number;

    abstract protected function getNumber($number);
}
