<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

use ArrayAccess;

class SkipArrayAccess
{
    /**
     * @param ArrayAccess $item
     */
    public function resolve($item)
    {
        return $item['name'];
    }

    /**
     * @param ArrayAccess|array<string, string> $item
     */
    public function resolve($item)
    {
        return $item['name'];
    }
}
