<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

final class KeepSideEffectOfAssignedCall
{
    private $connection;

    private $name;

    public function __construct(Factory $factory, string $name)
    {
        $this->connection = (object) $factory->connect();
        $this->name = $name;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

final class KeepSideEffectOfAssignedCall
{
    public function __construct(Factory $factory, string $name)
    {
        $factory->connect();
    }
}

?>
