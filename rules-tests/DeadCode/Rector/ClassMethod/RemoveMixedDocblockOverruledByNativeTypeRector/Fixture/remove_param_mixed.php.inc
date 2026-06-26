<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveMixedDocblockOverruledByNativeTypeRector\Fixture;

final class RemoveParamMixed
{
    /**
     * @param mixed $value
     */
    private function setProperty(object $object, string $property, int $value): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveMixedDocblockOverruledByNativeTypeRector\Fixture;

final class RemoveParamMixed
{
    private function setProperty(object $object, string $property, int $value): void
    {
    }
}

?>
