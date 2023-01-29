<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class SkipDynamicScalar
{
    public function getValue($number)
    {
        return $number ? 100 : (500 + $number);
    }
}
