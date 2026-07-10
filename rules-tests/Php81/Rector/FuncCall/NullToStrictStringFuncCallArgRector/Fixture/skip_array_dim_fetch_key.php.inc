<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class SkipArrayDimFetchKey
{
    public function run(array $data)
    {
        return str_replace('a', 'b', $data['key']);
    }
}
