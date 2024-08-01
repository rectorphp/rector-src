<?php

namespace Rector\Tests\Issues\CountArrayLongToShort\Fixture;

final class CountToEmptyArrayCompare
{
    public function run()
    {
        $data = [];

        if (count($data) === 0) {
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\CountArrayLongToShort\Fixture;

final class CountToEmptyArrayCompare
{
    public function run()
    {
        $data = [];

        if ($data === []) {
        }
    }
}

?>
