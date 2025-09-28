<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

class SkipWithFirstClassCallable
{
    public function go()
    {
        $this->run(['item1', 'item2']);

        $this->run(...)([1, 2]);
    }

    private function run(array $items)
    {
    }
}
