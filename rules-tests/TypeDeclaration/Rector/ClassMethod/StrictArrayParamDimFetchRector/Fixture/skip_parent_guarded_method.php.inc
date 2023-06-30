<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Source\SomeInterface;

class SkipParentGuardedMethod implements SomeInterface
{
    public function go($item)
    {
        return $item['name'];
    }
}
