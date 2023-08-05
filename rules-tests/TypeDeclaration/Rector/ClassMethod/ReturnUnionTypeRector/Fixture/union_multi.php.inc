<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

use DateTime;
use stdClass;

final class UnionMulti
{
    public function run()
    {
        if (rand(0, 1)) {
            return null;
        }

        if (rand(0, 1)) {
            return new DateTime('now');
        }

        return new stdClass;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

use DateTime;
use stdClass;

final class UnionMulti
{
    public function run(): null|\DateTime|\stdClass
    {
        if (rand(0, 1)) {
            return null;
        }

        if (rand(0, 1)) {
            return new DateTime('now');
        }

        return new stdClass;
    }
}

?>
