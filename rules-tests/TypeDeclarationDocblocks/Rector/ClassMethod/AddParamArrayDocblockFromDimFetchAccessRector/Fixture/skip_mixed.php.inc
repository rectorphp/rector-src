<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDimFetchAccessRector\Fixture;

final class SkipMixed
{
    public function process(array $data, int|string $key): void
    {
        $item = $data[$key];
    }
}
