<?php

namespace Rector\Tests\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector\Fixture;

class SkipEmptyCheck
{
    public function run($check)
    {
        if ($check) {
            $data = 'value';
        }

        return ! empty($data)
            ? $data
            : 'default';
    }

    public function run2()
    {
        if (empty($data)) {
            $data[] = 'test';
        }

        return $data;
    }
}
