<?php

namespace Rector\Core\Tests\Issues\DefaultValueSpaceShip\Fixture;

class Fixture
{
    public function sortRank($a, $b)
    {
        if ($a == $b) {
            return 0;
        }

        return ($a > $b) ? -1 : 1;
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\DefaultValueSpaceShip\Fixture;

class Fixture
{
    public function sortRank($a, $b)
    {
        return $b <=> $a;
    }
}

?>
