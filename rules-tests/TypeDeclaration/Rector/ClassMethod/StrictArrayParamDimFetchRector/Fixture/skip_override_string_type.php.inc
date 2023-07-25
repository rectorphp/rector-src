<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipOverrideStringType
{
    public function run($productOptions)
    {
        $productOptions = "hello";
        $productOptions['list'] = $productOptions['list'] ?? 'list';
    }
}
