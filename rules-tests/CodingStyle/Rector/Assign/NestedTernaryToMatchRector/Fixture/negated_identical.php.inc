<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\NestedTernaryToMatchRector\Fixture;

final class NegatedIdentical
{
    public function run($value)
    {
        $result = $value !== 10 ? 'equal to 10' : ($value !== 5 ? 'equal to 5' : 'other value');
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\NestedTernaryToMatchRector\Fixture;

final class NegatedIdentical
{
    public function run($value)
    {
        $result = match (true) {
            $value !== 10 => 'equal to 10',
            $value !== 5 => 'equal to 5',
            default => 'other value',
        };
    }
}

?>
