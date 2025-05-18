<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

class Fixture
{
    public function run(int $firstValue, int $secondValue)
    {
         $isSame = $firstValue == $secondValue;
         $isDifferent = $firstValue != $secondValue;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

class Fixture
{
    public function run(int $firstValue, int $secondValue)
    {
         $isSame = $firstValue === $secondValue;
         $isDifferent = $firstValue !== $secondValue;
    }
}

?>
