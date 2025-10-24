<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\NestedTernaryToMatchTrueRector\Fixture;

final class NestedTernaryToMatchTrueRector
{
    public function run($value)
    {
        $value = 5;

        $result = $value > 10 ? 'greater than 10' : ($value === 10 ? 'equal to 10' : 'less than 10');
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\NestedTernaryToMatchTrueRector\Fixture;

final class NestedTernaryToMatchTrueRector
{
    public function run($value)
    {
        $value = 5;

        $result = match (true) {
            $value > 10 => 'greater than 10',
            $value === 10 => 'equal to 10',
            default => 'less than 10',
        };
    }
}

?>
