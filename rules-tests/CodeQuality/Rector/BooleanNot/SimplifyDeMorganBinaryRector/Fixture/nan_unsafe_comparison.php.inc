<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector\Fixture;

final class NanUnsafeComparison
{
    public function run(float $a, array $b, array $c)
    {
        $result = !($a > 20 || $b < $c);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector\Fixture;

final class NanUnsafeComparison
{
    public function run(float $a, array $b, array $c)
    {
        $result = !($a > 20) && !($b < $c);
    }
}

?>
