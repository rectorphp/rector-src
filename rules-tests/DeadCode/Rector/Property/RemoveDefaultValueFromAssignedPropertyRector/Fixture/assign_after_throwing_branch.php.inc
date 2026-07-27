<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

use RuntimeException;

final class AssignAfterThrowingBranch
{
    private ?string $name = null;

    public function __construct(string $name, bool $shouldFail)
    {
        if ($shouldFail) {
            throw new RuntimeException('nope');
        }

        $this->name = $name;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

use RuntimeException;

final class AssignAfterThrowingBranch
{
    private ?string $name;

    public function __construct(string $name, bool $shouldFail)
    {
        if ($shouldFail) {
            throw new RuntimeException('nope');
        }

        $this->name = $name;
    }
}

?>
