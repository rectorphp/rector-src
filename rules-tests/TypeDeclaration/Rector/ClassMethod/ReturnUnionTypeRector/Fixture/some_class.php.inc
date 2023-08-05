<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

use stdClass;

final class SomeClass
{
    public function run()
    {
        if (rand(0, 1)) {
            return null;
        }

        return new stdClass;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

use stdClass;

final class SomeClass
{
    public function run(): ?\stdClass
    {
        if (rand(0, 1)) {
            return null;
        }

        return new stdClass;
    }
}

?>
