<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessReadOnlyTagRector\Fixture;

/**
 * @readonly
 */
final readonly class RemoveOnClass
{
    public function __construct(
        private string $name
    )
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessReadOnlyTagRector\Fixture;

final readonly class RemoveOnClass
{
    public function __construct(
        private string $name
    )
    {
    }
}

?>
