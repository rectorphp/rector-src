<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ArrayExplicitBoolCompareRector\Fixture;

final class ArrayCompare
{
    public function run(array $items)
    {
        if (!$items) {
            return 'no items';
        }

        if ($items) {
            return 'many items';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ArrayExplicitBoolCompareRector\Fixture;

final class ArrayCompare
{
    public function run(array $items)
    {
        if ($items === []) {
            return 'no items';
        }

        if ($items !== []) {
            return 'many items';
        }
    }
}

?>
