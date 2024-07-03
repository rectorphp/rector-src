<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StringReturnTypeFromStrictStringReturnsRector\Fixture;

final class SkipOnlyStrings
{
    public function run($value)
    {
        if ($value) {
            return 'yes';
        }

        return 'no';
    }
}
