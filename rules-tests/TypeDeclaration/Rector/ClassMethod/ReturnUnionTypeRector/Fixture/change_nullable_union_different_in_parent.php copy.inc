<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

class ChangeNullableUnionEqualInParent
{
    public function run()
    {
        if (rand(0, 1)) {
            return null;
        }

        return new \stdClass;
    }
}

final class ChangeNullableUnionEqualInParentChild extends ChangeNullableUnionEqualInParent
{
    public function run()
    {
        if (rand(0, 1)) {
            return null;
        }

        return new \stdClass;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

class ChangeNullableUnionEqualInParent
{
    public function run(): ?\stdClass
    {
        if (rand(0, 1)) {
            return null;
        }

        return new \stdClass;
    }
}

final class ChangeNullableUnionEqualInParentChild extends ChangeNullableUnionEqualInParent
{
    public function run(): ?\stdClass
    {
        if (rand(0, 1)) {
            return null;
        }

        return new \stdClass;
    }
}

?>