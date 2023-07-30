<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipPossibleString
{
    public function resolve($item)
    {
        $item .= 'yes';

        return $item[0];
    }
}
