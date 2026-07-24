<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

use stdClass;

final class NullableTypedProperty
{
    private ?stdClass $value = null;

    public function __construct(stdClass $value)
    {
        $this->value = $value;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

use stdClass;

final class NullableTypedProperty
{
    private ?stdClass $value;

    public function __construct(stdClass $value)
    {
        $this->value = $value;
    }
}

?>
