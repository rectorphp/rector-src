<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamFromDimFetchKeyUseRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamFromDimFetchKeyUseRector\Source\SomeParentMethod;

final class SkipHasParentMethod extends SomeParentMethod
{
    public function process($key)
    {
        $items = [
            'first' => 'Firstitem',
            'second' => 'Seconditem',
        ];

        return $items[$key];
    }
}
