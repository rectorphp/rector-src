<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

class SkipTyped
{
    public function resolve(object $item)
    {
        return $item['name'];
    }
}

?>