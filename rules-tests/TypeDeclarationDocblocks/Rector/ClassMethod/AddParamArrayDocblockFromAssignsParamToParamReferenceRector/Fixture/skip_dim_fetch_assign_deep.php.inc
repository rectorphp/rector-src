<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromAssignsParamToParamReferenceRector\Fixture;

final class SkipDimFetchAssignDeep
{
    public function run(array &$items)
    {
        $items[][] = 'John';
    }
}
