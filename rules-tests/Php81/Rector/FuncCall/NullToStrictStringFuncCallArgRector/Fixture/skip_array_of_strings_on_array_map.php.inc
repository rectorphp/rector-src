<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class SkipArrayOfStringsOnArrayMap
{
    public function foo(string $name)
    {
        $parts = explode('_', $name);
        return implode(
            '',
            array_map(
                fn ($part): string =>
                strtolower($part),
                $parts
            )
        );
    }
}
