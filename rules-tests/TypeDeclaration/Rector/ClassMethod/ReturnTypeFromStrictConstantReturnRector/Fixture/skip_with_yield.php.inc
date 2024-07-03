<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictConstantReturnRector\Fixture;

final class SkipWithYield
{
    private const NAME = 'name';

    public function get()
    {
        yield [];

        return self::NAME;
    }
}
