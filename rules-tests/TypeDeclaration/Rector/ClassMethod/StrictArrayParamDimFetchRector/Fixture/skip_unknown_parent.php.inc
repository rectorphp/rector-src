<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Source\SomeInterface;

class SkipUnknownParent extends UnknownParentClass
{
    public function go($item)
    {
        return $item['name'];
    }
}
