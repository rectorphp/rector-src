<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

final class AnonymousClassAndOtherObject2
{
    public function run()
    {
        if (rand(0,1)) {
            return new SkipAnonymousClassAndOtherObject();
        }

        $obj = new class {};
        return $obj;
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

final class AnonymousClassAndOtherObject2
{
    public function run(): object
    {
        if (rand(0,1)) {
            return new SkipAnonymousClassAndOtherObject();
        }

        $obj = new class {};
        return $obj;
    }
}
