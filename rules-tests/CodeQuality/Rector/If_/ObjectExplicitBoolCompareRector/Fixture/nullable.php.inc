<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ObjectExplicitBoolCompareRector\Fixture;

final class Nullable
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

    public function away(?string $item)
    {
        if (!$item) {
            return 'empty';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ObjectExplicitBoolCompareRector\Fixture;

final class Nullable
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

    public function away(?string $item)
    {
        if (!$item) {
            return 'empty';
        }
    }
}

?>
