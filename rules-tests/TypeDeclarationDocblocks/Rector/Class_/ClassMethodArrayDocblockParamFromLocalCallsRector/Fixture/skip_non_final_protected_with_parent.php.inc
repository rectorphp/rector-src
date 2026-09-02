<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Source\SafeParentClass;

class HandleNonFinalProtectedWithParent extends SafeParentClass
{
    public function go()
    {
        $this->run(['item1', 'item2']);
    }

    protected function run(array $items)
    {
    }
}
