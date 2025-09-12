<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockGetterReturnArrayFromPropertyDocblockVarRector\Fixture;

final class SkipNullableMixedArray
{
    private ?array $names = [];

    public function getNames(): array
    {
        return $this->names;
    }
}
