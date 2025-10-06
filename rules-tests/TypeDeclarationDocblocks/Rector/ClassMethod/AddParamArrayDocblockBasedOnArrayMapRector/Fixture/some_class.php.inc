<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockBasedOnArrayMapRector\Fixture;

final class SomeClass
{
    public function run(array $items): void
    {
        array_map(fn (string $item) => trim($item), $items);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockBasedOnArrayMapRector\Fixture;

final class SomeClass
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
