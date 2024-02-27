<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class SkipExecutionOp
{
    public function run(string $search, string $replace)
    {
        str_replace($search, $replace, `ls -l`);
    }
}