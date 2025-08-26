<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrowFunctionParamArrayWhereDimFetchRector\Fixture;

final class SkipIntegerIndexOnString
{
    public function run()
    {
        $username = 'john.doe';

        $initials = implode(
            '',
            array_map(
                static fn ($name): string => strtoupper((string) $name[0]),
                explode('.', $username),
            ),
        );
    }
}
