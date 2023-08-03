<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class SkipReturnDocblock
{
    public function getData()
    {
        return getMeBool2();
    }
}

/**
 * @return bool
 */
function getMeBool2()
{
}
