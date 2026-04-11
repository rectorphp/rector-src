<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

use stdClass as std;

final class WithAlias
{
    function run()
    {
        /** @var std $std */
        $std = new std();
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

use stdClass as std;

final class WithAlias
{
    function run()
    {
        $std = new std();
    }
}

?>
