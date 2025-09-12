<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

class SkipNonArrayParam
{
    public function go()
    {
        $this->run(['item1', 'item2']);
    }

    private function run($items)
    {
    }
}
