<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

/**
 * @phpstan-type ConfigArray array{is_dry: bool}
 */
final class RemoveObjectReturnNextToTypeAlias
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

/**
 * @phpstan-type ConfigArray array{is_dry: bool}
 */
final class RemoveObjectReturnNextToTypeAlias
{
    public function getName(): string
    {
        return 'name';
    }
}

?>
