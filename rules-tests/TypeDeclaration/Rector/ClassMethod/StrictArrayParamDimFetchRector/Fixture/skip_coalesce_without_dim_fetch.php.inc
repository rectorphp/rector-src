<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipCoalesceWithoutDimFetch
{
    public function run($productOptions)
    {
        $productOptions = $productOptions ?? [];

        $productOptions['list'] = $productOptions['list'] ?? 'list';
    }
}
