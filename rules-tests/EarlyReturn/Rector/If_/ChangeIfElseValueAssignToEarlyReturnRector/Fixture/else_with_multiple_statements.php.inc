<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\ChangeIfElseValueAssignToEarlyReturnRector\Fixture;

class ElseWithMultipleStatementsFixture
{
    public function generateNumber(int $bonus, int $multiplier): int
    {
        if (mt_rand(0, 1) === 1) {
            $number = 10;
        } else {
            $bonus *= $multiplier;
            $number = 20 + $bonus;
        }

        return $number;
    }
}

?>
-----
<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\ChangeIfElseValueAssignToEarlyReturnRector\Fixture;

class ElseWithMultipleStatementsFixture
{
    public function generateNumber(int $bonus, int $multiplier): int
    {
        if (mt_rand(0, 1) === 1) {
            return 10;
        }
        $bonus *= $multiplier;

        return 20 + $bonus;
    }
}

?>
