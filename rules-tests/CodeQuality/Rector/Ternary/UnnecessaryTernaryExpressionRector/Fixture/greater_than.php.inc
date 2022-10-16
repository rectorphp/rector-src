<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\UnnecessaryTernaryExpressionRector\Fixture;

class GreaterThan
{
    public function run($first, $second)
    {
        return $first > $second ? true : false;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\UnnecessaryTernaryExpressionRector\Fixture;

class GreaterThan
{
    public function run($first, $second)
    {
        return $first > $second;
    }
}

?>
