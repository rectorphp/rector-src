<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

class FromExactValue
{
    public function run()
    {
        $firstValue = 1;
        $secondValue = 2;

        $isSame = $firstValue == $secondValue;
        $isDiffernt = $firstValue != $secondValue;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

class FromExactValue
{
    public function run()
    {
        $firstValue = 1;
        $secondValue = 2;

        $isSame = $firstValue === $secondValue;
        $isDiffernt = $firstValue !== $secondValue;
    }
}

?>
