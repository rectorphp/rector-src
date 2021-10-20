<?php

namespace Rector\Tests\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector\Fixture;

class SkipIssetCheck
{
    public function run($check)
    {
        if ($check) {
            $data = 'value';
        }

        return isset($data)
            ? $data
            : 'default';
    }
}
