<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector\Source\SomeClassWithReturnMixed;

class MyClass extends SomeClassWithReturnMixed
{
    public function run()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector\Source\SomeClassWithReturnMixed;

class MyClass extends SomeClassWithReturnMixed
{
    public function run(): mixed
    {
    }
}

?>
