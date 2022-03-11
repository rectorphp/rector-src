<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Source\vendor\SomeClass;

class SkipExtendsVendor extends SomeClass
{
    protected function getValues()
    {
    }
}
