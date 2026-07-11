<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class RemoveObjectReturnOverString
{
    /**
     * @return \stdClass
     */
    public function getName(): string
    {
        return 'name';
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class RemoveObjectReturnOverString
{
    public function getName(): string
    {
        return 'name';
    }
}

?>
