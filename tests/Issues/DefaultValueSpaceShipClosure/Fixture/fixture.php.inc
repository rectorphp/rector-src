<?php

namespace Rector\Core\Tests\Issues\DefaultValueSpaceShipClosure\Fixture;

class Fixture
{
    public function run()
    {
        uasort([], function ($a, $b) {
            if ($a == $b) {
                return 0;
            }

            return ($a > $b) ? -1 : 1;
        });
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\DefaultValueSpaceShipClosure\Fixture;

class Fixture
{
    public function run()
    {
        uasort([], fn($a, $b) => $b <=> $a);
    }
}

?>
