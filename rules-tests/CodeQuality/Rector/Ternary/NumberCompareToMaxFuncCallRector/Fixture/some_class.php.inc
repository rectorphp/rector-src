<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\NumberCompareToMaxFuncCallRector\Fixture;

class SomeClass
{
    public function run(int $value)
    {
        return $value > 100 ? $value : 100;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\NumberCompareToMaxFuncCallRector\Fixture;

class SomeClass
{
    public function run(int $value)
    {
        return max($value, 100);
    }
}

?>
