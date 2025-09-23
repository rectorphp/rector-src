<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class SkipParamArrayOverride
{
    public function go(array $items)
    {
        $this->run(['item1', 'item2']);
    }

    /**
     * @param int[]|string[] $items
     */
    private function run(array $items)
    {
    }
}
