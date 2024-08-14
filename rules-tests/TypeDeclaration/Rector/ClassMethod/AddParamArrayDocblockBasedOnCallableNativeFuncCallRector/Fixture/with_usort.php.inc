<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\Fixture;

use stdClass;

final class WithUsort
{
    public function process(array $classes)
    {
        usort($classes, function (stdClass $stdClass, stdClass $stdClass2): int {
            return 1;
        });
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\Fixture;

use stdClass;

final class WithUsort
{
    /**
     * @param \stdClass[] $classes
     */
    public function process(array $classes)
    {
        usort($classes, function (stdClass $stdClass, stdClass $stdClass2): int {
            return 1;
        });
    }
}

?>
