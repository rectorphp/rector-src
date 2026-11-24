<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\NegatedAndsToPositiveOrsRector\Fixture;

final class NanUnsafeComparison
{
    public function run(float $a, $b, $c)
    {
        $result = !($a > 20 && $b < $c);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\NegatedAndsToPositiveOrsRector\Fixture;

final class NanUnsafeComparison
{
    public function run(float $a, $b, $c)
    {
        $result = !($a > 20) || !($b < $c);
    }
}

?>
