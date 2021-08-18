<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitBoolCompareRector\Fixture;

final class StringKnownvalue
{
    public function run(string $item)
    {
        $item = 'a value';

        if (!$item) {
            return 'empty';
        }

        if ($item) {
            return 'not empty';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitBoolCompareRector\Fixture;

final class StringKnownvalue
{
    public function run(string $item)
    {
        $item = 'a value';

        if ($item === '') {
            return 'empty';
        }

        if ($item !== '') {
            return 'not empty';
        }
    }
}

?>
