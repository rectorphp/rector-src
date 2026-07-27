<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class AssignInIfElseIfElse
{
    private ?string $name = null;

    public function __construct(int $type)
    {
        if ($type === 1) {
            $this->name = 'one';
        } elseif ($type === 2) {
            $this->name = 'two';
        } else {
            $this->name = 'many';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class AssignInIfElseIfElse
{
    private ?string $name;

    public function __construct(int $type)
    {
        if ($type === 1) {
            $this->name = 'one';
        } elseif ($type === 2) {
            $this->name = 'two';
        } else {
            $this->name = 'many';
        }
    }
}

?>
