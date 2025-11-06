<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\NestedTernaryToMatchRector\Fixture;

final class NestedTernaryToMatchRector
{
    public function run($value)
    {
        $result = $value > 10 ? 'greater than 10' : ($value === 10 ? 'equal to 10' : 'less than 10');
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\NestedTernaryToMatchRector\Fixture;

final class NestedTernaryToMatchRector
{
    public function run($value)
    {
        $result = match (true) {
            $value > 10 => 'greater than 10',
            $value === 10 => 'equal to 10',
            default => 'less than 10',
        };
    }
}

?>
