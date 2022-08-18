<?php

namespace Rector\Tests\EarlyReturn\Rector\StmtsAwareInterface\ReturnEarlyIfVariableRector\Fixture;

final class SomeClass
{
    public function run($value)
    {
        if ($value === 50) {
            $value = 100;
        }

        return $value;
    }
}

?>
-----
<?php

namespace Rector\Tests\EarlyReturn\Rector\StmtsAwareInterface\ReturnEarlyIfVariableRector\Fixture;

final class SomeClass
{
    public function run($value)
    {
        if ($value === 50) {
            return 100;
        }

        return $value;
    }
}

?>
