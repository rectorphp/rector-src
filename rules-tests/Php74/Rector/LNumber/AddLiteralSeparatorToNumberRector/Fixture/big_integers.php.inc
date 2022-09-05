<?php

namespace Rector\Tests\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector\Fixture;

final class BigIntegers
{
    public function run()
    {
        $int2 = 1000000;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector\Fixture;

final class BigIntegers
{
    public function run()
    {
        $int2 = 1_000_000;
    }
}

?>
