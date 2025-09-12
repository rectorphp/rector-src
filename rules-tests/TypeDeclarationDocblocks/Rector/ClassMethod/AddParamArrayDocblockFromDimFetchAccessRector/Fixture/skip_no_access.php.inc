<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDimFetchAccessRector\Fixture;

final class SkipNoAccess
{
    public function process(array $data): void
    {
        $data[] = '111';
    }
}
