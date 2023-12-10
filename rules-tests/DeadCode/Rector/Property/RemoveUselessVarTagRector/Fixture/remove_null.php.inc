<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

final class RemoveNull
{
    /**
     * @var null
     */
    public $name = null;
}

?>
----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

final class RemoveNull
{
    public $name = null;
}

?>

