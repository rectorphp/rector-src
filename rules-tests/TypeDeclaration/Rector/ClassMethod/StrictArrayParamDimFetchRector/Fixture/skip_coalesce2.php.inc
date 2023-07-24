<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipCoalesce2
{
    public function run($productOptions)
    {
        $productOptions ??= [];

        $productOptions['list'] = $productOptions['list'] ?? 'list';
    }
}
