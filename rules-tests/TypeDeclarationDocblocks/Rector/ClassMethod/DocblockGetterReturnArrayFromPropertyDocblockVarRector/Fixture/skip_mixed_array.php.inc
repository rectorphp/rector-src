<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockGetterReturnArrayFromPropertyDocblockVarRector\Fixture;

final class SkipMixedArray
{
    private array $names = [];

    public function getNames(): array
    {
        return $this->names;
    }
}
