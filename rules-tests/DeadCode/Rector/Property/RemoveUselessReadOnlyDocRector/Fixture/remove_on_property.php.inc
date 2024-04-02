<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessReadOnlyDocRector\Fixture;

final class RemoveOnProperty
{
    /**
     * @readonly
     */
    private readonly string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessReadOnlyDocRector\Fixture;

final class RemoveOnProperty
{
    private readonly string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}

?>
