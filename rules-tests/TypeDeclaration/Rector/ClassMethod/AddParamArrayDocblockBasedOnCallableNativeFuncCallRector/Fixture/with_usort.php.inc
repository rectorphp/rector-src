<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\Fixture;

use stdClass;

final class WithUsort
{
    public function process(array $items)
    {
        usort($items, function (stdClass $item) {
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
     * @param \stdClass[] $items
     */
    public function process(array $items)
    {
        usort($items, function (stdClass $item) {
        });
    }
}

?>
