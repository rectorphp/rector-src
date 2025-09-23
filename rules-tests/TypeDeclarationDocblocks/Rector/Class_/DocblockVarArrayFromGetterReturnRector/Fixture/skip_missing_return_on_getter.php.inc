<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromGetterReturnRector\Fixture;

final class SkipMissingReturnOnGetter
{
    private array $names;

    public function getNames(): array
    {
        return $this->names;
    }
}
