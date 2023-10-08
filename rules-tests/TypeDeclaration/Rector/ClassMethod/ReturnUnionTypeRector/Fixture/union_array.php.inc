<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

use DateTime;
use stdClass;

final class UnionArray
{
    public function run($a, $b)
    {
        if ($a) {
            return null;
        }

        if ($b) {
            return new DateTime('now');
        }

        return [];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

use DateTime;
use stdClass;

final class UnionArray
{
    public function run($a, $b): \DateTime|array|null
    {
        if ($a) {
            return null;
        }

        if ($b) {
            return new DateTime('now');
        }

        return [];
    }
}

?>
