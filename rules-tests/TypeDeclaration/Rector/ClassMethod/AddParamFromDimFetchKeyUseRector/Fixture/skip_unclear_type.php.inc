<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamFromDimFetchKeyUseRector\Fixture;

final class SkipUnclearType
{
    public function process($key, $items)
    {
        return $items[$key];
    }
}
