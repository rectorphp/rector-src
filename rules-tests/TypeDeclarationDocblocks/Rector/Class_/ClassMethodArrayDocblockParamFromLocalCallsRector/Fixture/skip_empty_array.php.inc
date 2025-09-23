<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class SkipEmptyArray
{
    public function go()
    {
        $this->run([]);
    }

    private function run(array $items)
    {
    }
}
