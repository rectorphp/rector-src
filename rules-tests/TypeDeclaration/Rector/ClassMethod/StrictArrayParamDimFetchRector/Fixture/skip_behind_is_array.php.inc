<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipBehindIsArray
{
    public function resolve($item)
    {
        if (is_array($item)) {
            return $item['name'];
        }

        return $item;
    }
}
