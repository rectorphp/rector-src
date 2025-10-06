<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockBasedOnArrayMapRector\Fixture;

final class OverrideMixedType
{
    /**
     * @param mixed[] $items
     */
    public function run(array $items): void
    {
        array_map(fn (string $item) => trim($item), $items);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockBasedOnArrayMapRector\Fixture;

final class OverrideMixedType
{
    /**
     * @param string[] $items
     */
    public function run(array $items): void
    {
        array_map(fn (string $item) => trim($item), $items);
    }
}

?>
