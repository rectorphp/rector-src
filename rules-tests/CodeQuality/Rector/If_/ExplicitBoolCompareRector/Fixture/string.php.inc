<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitBoolCompareRector\Fixture;

final class ExplicitString
{
    public function run(string $item)
    {
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

final class ExplicitString
{
    public function run(string $item)
    {
        if ($item === '' || $item === '0') {
            return 'empty';
        }

        if ($item !== '' && $item !== '0') {
            return 'not empty';
        }
    }
}

?>
