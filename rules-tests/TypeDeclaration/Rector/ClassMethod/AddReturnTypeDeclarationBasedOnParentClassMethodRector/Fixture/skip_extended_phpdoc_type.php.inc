<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector\Fixture;

use Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector\Source\SomeClassWithPHPDocReturnType;

class MyClass extends SomeClassWithPHPDocReturnType
{
    public function run()
    {
    }
}

?>
