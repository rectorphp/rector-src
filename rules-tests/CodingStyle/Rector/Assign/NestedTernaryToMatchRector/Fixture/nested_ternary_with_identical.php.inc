<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\NestedTernaryToMatchRector\Fixture;

final class NestedTernaryWithIdentical
{
    public function run($value)
    {
        $result = $value === 10 ? 'equal to 10' : ($value === 5 ? 'equal to 5' : 'other value');
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\NestedTernaryToMatchRector\Fixture;

final class NestedTernaryWithIdentical
{
    public function run($value)
    {
        $result = match ($value) {
            10 => 'equal to 10',
            5 => 'equal to 5',
            default => 'other value',
        };
    }
}

?>
