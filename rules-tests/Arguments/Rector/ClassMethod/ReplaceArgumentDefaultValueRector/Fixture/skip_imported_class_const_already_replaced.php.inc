<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Source\SomeSortableObject;

class SkipImportedClassConstAlreadyReplaced
{
    public function run()
    {
        $object = new SomeSortableObject();
        $object->sortBy(SomeSortableObject::SORT_ORDER_DESC);
    }
}
