<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\NegatedAndsToPositiveOrsRector\Fixture;

class Fixture
{
    public function run(int $a, int $b, int $c)
    {
        $result = !($a > 20 && $b <= 50);
        $d = !($a === null && $b < $c);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\NegatedAndsToPositiveOrsRector\Fixture;

class Fixture
{
    public function run(int $a, int $b, int $c)
    {
        $result = $a <= 20 || $b > 50;
        $d = $a !== null || $b >= $c;
    }
}

?>
