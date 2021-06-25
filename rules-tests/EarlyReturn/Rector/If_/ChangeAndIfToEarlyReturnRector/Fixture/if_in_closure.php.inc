<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector\Fixture;

class IfInClosure
{
    public function run()
    {
        function (Car $car) {
            if ($car->hasWheels && $car->hasFuel) {
                return true;
            }
            return false;
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector\Fixture;

class IfInClosure
{
    public function run()
    {
        function (Car $car) {
            if (!$car->hasWheels) {
                return false;
            }
            if (!$car->hasFuel) {
                return false;
            }
            return true;
        };
    }
}

?>
