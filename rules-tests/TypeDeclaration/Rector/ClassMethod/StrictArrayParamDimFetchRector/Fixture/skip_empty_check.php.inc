<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipEmptyCheck
{
    public function run($productOptions)
    {
        if (empty($productOptions)) {
            return [];
        }

        echo $productOptions['list'][0];
    }
}
