<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\NestedTernaryToMatchTrueRector\Fixture;

final class NestedTernaryWithIdentical
{
    public function run($value)
    {
        $result = $value === 10 ? 'equal to 10' : ($value === 5 ? 'equal to 10' : 'other value');
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\NestedTernaryWithIdentical\Fixture;

final class NestedTernaryWithIdentical
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
