<?php

namespace Rector\Tests\Php70\Rector\If_\IfToSpaceshipRector\Fixture;

class Fixture
{
    public function run(array $languages)
    {
        usort($languages, function ($a, $b) {
            if ($a[0] == $b[0]) {
                return 0;
            }

            return ($a[0] < $b[0]) ? 1 : -1;
        });
    }
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\If_\IfToSpaceshipRector\Fixture;

class Fixture
{
    public function run(array $languages)
    {
        usort($languages, function ($a, $b) {
            return $b[0] <=> $a[0];
        });
    }
}

?>
