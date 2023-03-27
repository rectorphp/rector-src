<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfExactValueReturnValueRector\Fixture;

final class MultipleIfs
{
    public function run($value)
    {
        if (rand(0, 1)) {
            return 0;
        }

        echo 'statement';

        if ($value === 93) {
            return 93;
        }

        return $value;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfExactValueReturnValueRector\Fixture;

final class MultipleIfs
{
    public function run($value)
    {
        if (rand(0, 1)) {
            return 0;
        }

        echo 'statement';

        return $value;
    }
}

?>
