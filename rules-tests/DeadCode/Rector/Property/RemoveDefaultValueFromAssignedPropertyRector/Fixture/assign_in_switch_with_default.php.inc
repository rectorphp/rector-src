<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class AssignInSwitchWithDefault
{
    private ?string $name = null;

    public function __construct(int $type)
    {
        switch ($type) {
            case 1:
                $this->name = 'one';
                break;
            default:
                $this->name = 'many';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class AssignInSwitchWithDefault
{
    private ?string $name;

    public function __construct(int $type)
    {
        switch ($type) {
            case 1:
                $this->name = 'one';
                break;
            default:
                $this->name = 'many';
        }
    }
}

?>
