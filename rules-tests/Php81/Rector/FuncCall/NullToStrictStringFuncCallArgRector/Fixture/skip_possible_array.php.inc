<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class SkipPossibleArray
{
    public function run(string|array|null $value): array|string
    {
        return str_replace('for', 'bar', $value);
    }
}
