<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector\Fixture;

final class DoubleNegate
{
    public function run()
    {
        if (! true === false) {
            return 'yes';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector\Fixture;

final class DoubleNegate
{
    public function run()
    {
        if (true) {
            return 'yes';
        }
    }
}

?>
