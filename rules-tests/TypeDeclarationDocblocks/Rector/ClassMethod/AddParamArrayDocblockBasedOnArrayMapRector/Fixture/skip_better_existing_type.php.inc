<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockBasedOnArrayMapRector\Fixture;

final class SkipBetterExistingType
{
    /**
     * @param array<string|\Stringable> $items
     */
    public function run(array $items): void
    {
        array_map(fn (string $item) => trim($item), $items);
    }
}
