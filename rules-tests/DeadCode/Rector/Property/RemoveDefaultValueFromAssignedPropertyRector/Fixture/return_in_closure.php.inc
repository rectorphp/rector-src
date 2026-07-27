<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class ReturnInClosure
{
    private ?string $name = null;

    public function __construct(string $name)
    {
        $this->name = (function () use ($name): string {
            return $name;
        })();
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class ReturnInClosure
{
    private ?string $name;

    public function __construct(string $name)
    {
        $this->name = (function () use ($name): string {
            return $name;
        })();
    }
}

?>
