<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromAssignsParamToParamReferenceRector\Fixture;

final class SkipMultipleAssigns
{
    private function run(array &$items)
    {
        $items[] = 'John';
        $items[] = 1000;
    }
}
