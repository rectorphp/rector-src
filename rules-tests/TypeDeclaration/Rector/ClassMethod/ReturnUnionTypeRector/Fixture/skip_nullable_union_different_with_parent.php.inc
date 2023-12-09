<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

use stdClass;

class DontChangeNullableUnionDifferentInParent
{
    public function run()
    {
        if (rand(0, 1)) {
            return null;
        }

        return new \DateTime;
    }
}

final class SkipNullableUnionDifferentWithParentChild extends DontChangeNullableUnionDifferentInParent
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

use stdClass;

class DontChangeNullableUnionDifferentInParent
{
    public function run()
    {
        if (rand(0, 1)) {
            return null;
        }

        return new \DateTime;
    }
}

final class SkipNullableUnionDifferentWithParentChild extends DontChangeNullableUnionDifferentInParent
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