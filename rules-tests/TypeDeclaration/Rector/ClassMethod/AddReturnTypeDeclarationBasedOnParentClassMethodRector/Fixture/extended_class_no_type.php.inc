<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector\Source\SomeClassWithoutReturnType;

class MyClass extends SomeClassWithoutReturnType
{
    public function run()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector\Source\SomeClassWithoutReturnType;

class MyClass extends SomeClassWithoutReturnType
{
    public function run()
    {
    }
}

?>
