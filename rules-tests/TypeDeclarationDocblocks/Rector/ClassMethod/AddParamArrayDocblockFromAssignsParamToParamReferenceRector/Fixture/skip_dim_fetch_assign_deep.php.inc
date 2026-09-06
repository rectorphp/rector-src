<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromAssignsParamToParamReferenceRector\Fixture;

final class SkipDimFetchAssignDeep
{
    private function run(array &$items)
    {
        $items[][] = 'John';
    }
}
