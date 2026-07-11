<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class RemoveObjectReturnOverArray
{
    /**
     * @return \stdClass
     */
    public function getItems(): array
    {
        return [];
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class RemoveObjectReturnOverArray
{
    public function getItems(): array
    {
        return [];
    }
}

?>
