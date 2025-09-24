<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromAssignsParamToParamReferenceRector\Fixture;

final class SkipDimFetchAssingDeep
{
    public function run(array &$items)
    {
        $items[][] = 'John';
    }
}
