<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

use DateTime;

/** @var DateTime */
$a = new DateTime('now');

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

use DateTime;

$a = new DateTime('now');

?>
