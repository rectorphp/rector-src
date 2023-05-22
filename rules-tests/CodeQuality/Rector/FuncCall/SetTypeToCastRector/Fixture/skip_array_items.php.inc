<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SetTypeToCastRector\Fixture;

class SkipArrayItems
{
    public function run($foo)
    {
        $result = [settype($foo, 'string')];
        $result = [0 => settype($foo, 'string')];
    }
}
