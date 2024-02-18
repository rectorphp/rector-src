<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class SkipPossibleArray2
{
    public function run(array|null $value)
    {
        return str_replace('for', 'bar', $value);
    }
}
