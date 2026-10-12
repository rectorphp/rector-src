<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

use ArrayAccess;

class SkipPrivateMethodCalledWithArrayAccessArg
{
    public function run(ArrayAccess $arrayAccess, array $data): void
    {
        $this->isTrue($arrayAccess);
        $this->isTrue($data);
    }

    private function isTrue($value): bool
    {
        return $value['asdf'] === true;
    }
}
