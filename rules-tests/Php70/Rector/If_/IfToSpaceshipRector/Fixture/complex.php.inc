<?php

namespace Rector\Tests\Php70\Rector\If_\IfToSpaceshipRector\Fixture;

class Complex
{
    public function run()
    {
        usort($languages, function ($a, $b) {
            if ($a[0] == $b[0]) {
                return 0;
            } else {
                return ($a[0] < $b[0]) ? 1 : -1;
            }
        });

        usort($languages, function ($a, $b) {
            if ($a[0] === $b[0]) {
                return 0;
            } else {
                return ($a[0] > $b[0]) ? -1 : 1;
            }
        });

        usort($languages, function($a, $b) {
            if (count($a) === count($b)) {
                return 0;
            }

            return count($a) > count($b) ? -1 : 1;
        });
    }
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\If_\IfToSpaceshipRector\Fixture;

class Complex
{
    public function run()
    {
        usort($languages, function ($a, $b) {
            return $b[0] <=> $a[0];
        });

        usort($languages, function ($a, $b) {
            return $b[0] <=> $a[0];
        });

        usort($languages, function ($a, $b) {
            return count($b) <=> count($a);
        });
    }
}

?>
