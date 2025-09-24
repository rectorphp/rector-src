<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class SkipEmptyIterableValueType
{
    public function run()
    {
        /** @var list $value */
        $value = [];

        $this->someCall($value);
    }

    private function someCall(array $value)
    {
    }
}
