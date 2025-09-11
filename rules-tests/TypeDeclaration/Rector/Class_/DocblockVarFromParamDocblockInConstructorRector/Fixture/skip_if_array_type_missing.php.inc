<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\DocblockVarFromParamDocblockInConstructorRector\Fixture;

final class SkipIfArrayTypeMissing
{
    private $names = [];

    /**
     * @param string[] $names
     */
    public function __construct(array $names)
    {
        $this->names = $names;
    }
}
