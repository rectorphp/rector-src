<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SetTypeToCastRector\Fixture;

class SkipAssign
{
    public function run($foo)
    {
        $result = settype($foo, 'string');
    }
}
