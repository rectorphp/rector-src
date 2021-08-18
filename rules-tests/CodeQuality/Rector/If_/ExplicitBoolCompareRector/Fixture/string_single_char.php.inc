<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitBoolCompareRector\Fixture;

final class StringSingleChar
{
    public function run(bool $check)
    {
        $item = $check
            ? 'a'
            : '0';

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

final class StringSingleChar
{
    public function run(bool $check)
    {
        $item = $check
            ? 'a'
            : '0';

        if ($item === '' || $item === '0') {
            return 'empty';
        }

        if ($item !== '' && $item !== '0') {
            return 'not empty';
        }
    }
}

?>
