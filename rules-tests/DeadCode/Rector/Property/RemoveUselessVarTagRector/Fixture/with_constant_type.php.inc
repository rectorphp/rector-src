<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

final class WithConstantType
{
    /**
     * @var string
     */
    public const string NAME = 'value';
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

final class WithConstantType
{
    public const string NAME = 'value';
}

?>
