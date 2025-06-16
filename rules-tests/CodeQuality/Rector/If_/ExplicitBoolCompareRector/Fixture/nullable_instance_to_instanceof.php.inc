<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitBoolCompareRector\Fixture;

final class NullableInstanceToInstanceof
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
            return 'known';
        }
    }
}



?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitBoolCompareRector\Fixture;

final class NullableInstanceToInstanceof
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
            return 'known';
        }
    }
}



?>
