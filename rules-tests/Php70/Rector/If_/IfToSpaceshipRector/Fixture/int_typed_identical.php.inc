<?php

namespace Rector\Tests\Php70\Rector\If_\IfToSpaceshipRector\Fixture;

final class IntTypedIdentical
{
    public function run(int $first, int $second)
    {
        if ($first === $second) {
            return 0;
        }

        return ($first < $second) ? -1 : 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\If_\IfToSpaceshipRector\Fixture;

final class IntTypedIdentical
{
    public function run(int $first, int $second)
    {
        return $first <=> $second;
    }
}

?>
