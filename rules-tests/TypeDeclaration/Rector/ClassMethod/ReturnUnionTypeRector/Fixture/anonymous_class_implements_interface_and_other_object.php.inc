<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Source\SomeAnonymousInterface;

final class AnonymousClassImplementsInterface
{
    public function run()
    {
        if (rand(0,1)) {
            return new AnonymousClassImplementsInterface();
        }

        return new class implements SomeAnonymousInterface{};
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Source\SomeAnonymousInterface;

final class AnonymousClassImplementsInterface
{
    public function run(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture\AnonymousClassImplementsInterface|\Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Source\SomeAnonymousInterface
    {
        if (rand(0,1)) {
            return new AnonymousClassImplementsInterface();
        }

        return new class implements SomeAnonymousInterface{};
    }
}

?>
