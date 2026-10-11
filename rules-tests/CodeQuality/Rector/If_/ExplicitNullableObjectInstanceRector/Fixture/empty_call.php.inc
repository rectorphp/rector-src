<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

final class EmptyCall
{
    public function run(?\stdClass $item)
    {
        if (empty($item)) {
            return 'empty';
        }
    }

    public function go(?\stdClass $item)
    {
        if (! empty($item)) {
            return 'not empty';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

final class EmptyCall
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
