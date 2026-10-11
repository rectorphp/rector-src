<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

final class IfTruthiness
{
    public function run(?\stdClass $item)
    {
        if (!$item) {
            return 'empty';
        }
    }

    public function go(?\stdClass $item)
    {
        if ($item) {
            return 'not empty';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

final class IfTruthiness
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
