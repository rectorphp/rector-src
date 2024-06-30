<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Source\SomeParentClass;

final class ExtendedClass extends SomeParentClass
{
    public function run()
    {
        if (rand(0, 1)) {
            return new ExtendedClass();
        }

        return new SomeParentClass();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Source\SomeParentClass;

final class ExtendedClass extends SomeParentClass
{
    public function run(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture\ExtendedClass|\Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Source\SomeParentClass
    {
        if (rand(0, 1)) {
            return new ExtendedClass();
        }

        return new SomeParentClass();
    }
}

?>
