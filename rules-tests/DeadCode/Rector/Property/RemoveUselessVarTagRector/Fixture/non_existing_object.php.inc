<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\Properties;

final class NonExistingObject
{
    /** @var SomeNonExistingObject */
    private Properties|null $properties;
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\Properties;

final class NonExistingObject
{
    private Properties|null $properties;
}

?>