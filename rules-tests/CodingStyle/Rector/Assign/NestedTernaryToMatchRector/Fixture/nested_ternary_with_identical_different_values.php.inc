<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\NestedTernaryToMatchRector\Fixture;

final class NestedTernaryWithIdenticalDifferentValues
{
    public function run($value, $nextValue)
    {
        $result = $value === 10 ? 'equal to 10' : ($nextValue === 5 ? 'equal to 5' : 'other value');
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\NestedTernaryToMatchRector\Fixture;

final class NestedTernaryWithIdenticalDifferentValues
{
    public function run($value, $nextValue)
    {
        $result = match (true) {
            $value === 10 => 'equal to 10',
            $nextValue === 5 => 'equal to 5',
            default => 'other value',
        };
    }
}

?>
