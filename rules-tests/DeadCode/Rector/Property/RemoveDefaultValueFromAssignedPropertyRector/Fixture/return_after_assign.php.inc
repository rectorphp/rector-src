<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class ReturnAfterAssign
{
    private ?string $name = null;

    public function __construct(string $name, bool $shouldReturn = false)
    {
        $this->name = $name;

        if ($shouldReturn) {
            return;
        }

        $this->name = strtoupper($name);
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class ReturnAfterAssign
{
    private ?string $name;

    public function __construct(string $name, bool $shouldReturn = false)
    {
        $this->name = $name;

        if ($shouldReturn) {
            return;
        }

        $this->name = strtoupper($name);
    }
}

?>
