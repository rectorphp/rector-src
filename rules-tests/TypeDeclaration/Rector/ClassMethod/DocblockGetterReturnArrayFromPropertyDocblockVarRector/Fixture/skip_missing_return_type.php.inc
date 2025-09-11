<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\DocblockGetterReturnArrayFromPropertyDocblockVarRector\Fixture;

final class SkipMissingPropertyType
{
    /**
     * @var string[]
     */
    private array $names = [];

    public function getNames()
    {
        return $this->names;
    }
}
