<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector\Fixture;

final class SelfAssignNegation
{
    public function run(bool $value, array $items)
    {
        $match = in_array($value, $items, true);
        $match = $match !== true;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector\Fixture;

final class SelfAssignNegation
{
    public function run(bool $value, array $items)
    {
        $match = in_array($value, $items, true);
        $match = !$match;
    }
}

?>
