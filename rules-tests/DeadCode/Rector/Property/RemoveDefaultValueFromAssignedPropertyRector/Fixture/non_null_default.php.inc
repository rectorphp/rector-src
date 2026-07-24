<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class NonNullDefault
{
    private int $value = 5;

    public function __construct(int $value)
    {
        $this->value = $value;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class NonNullDefault
{
    private int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }
}

?>
