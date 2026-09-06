<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromAssignsParamToParamReferenceRector\Fixture;

final class SkipNonPrivateMethod
{
    protected function addMobileData(array &$data): void
    {
        $data[] = 'value';
    }
}
