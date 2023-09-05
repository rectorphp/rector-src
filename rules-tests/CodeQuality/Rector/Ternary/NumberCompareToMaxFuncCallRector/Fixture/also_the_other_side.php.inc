<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\NumberCompareToMaxFuncCallRector\Fixture;

final class AlsoTheOtherSide
{
    public function run(int $value)
    {
        return 100 < $value ? $value : 100;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\NumberCompareToMaxFuncCallRector\Fixture;

final class AlsoTheOtherSide
{
    public function run(int $value)
    {
        return max($value, 100);
    }
}

?>
