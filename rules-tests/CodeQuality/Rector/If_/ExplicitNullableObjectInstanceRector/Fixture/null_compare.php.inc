<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

final class NullCompare
{
    public function run(?\stdClass $item)
    {
        if ($item === null) {
            return 'empty';
        }
    }

    public function go(?\stdClass $item)
    {
        if ($item !== null) {
            return 'not empty';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

final class NullCompare
{
    public function run(?\stdClass $item)
    {
        if (!$item instanceof \stdClass) {
            return 'empty';
        }
    }

    public function go(?\stdClass $item)
    {
        if ($item instanceof \stdClass) {
            return 'not empty';
        }
    }
}

?>
