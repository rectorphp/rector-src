<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessReadOnlyTagRector\Fixture;

final class RemoveOnParamConstruct
{
    public function __construct(
        /**
         * @readonly
         */
        private readonly string $name
    )
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessReadOnlyTagRector\Fixture;

final class RemoveOnParamConstruct
{
    public function __construct(
        private readonly string $name
    )
    {
    }
}

?>
