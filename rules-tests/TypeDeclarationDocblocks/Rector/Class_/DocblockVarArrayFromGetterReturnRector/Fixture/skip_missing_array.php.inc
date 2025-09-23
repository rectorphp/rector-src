<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromGetterReturnRector\Fixture;

final class SkipMissingArray
{
    private $names;

    /**
     * @return string[]
     */
    public function getNames(): array
    {
        return $this->names;
    }
}
