<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

final class AnonymousClassAndOtherObject
{
    public function run()
    {
        if (rand(0,1)) {
            return new SkipAnonymousClassAndOtherObject();
        }

        return new class {};
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

final class AnonymousClassAndOtherObject
{
    public function run(): object
    {
        if (rand(0,1)) {
            return new SkipAnonymousClassAndOtherObject();
        }

        return new class {};
    }
}
