<?php

namespace Rector\Tests\Php83\Rector\FuncCall\DynamicClassConstFetchRector\Fixture;

class SkipDynamicClassConstFetch
{
    public const SOME_CONST = 'some_value';

    public function test()
    {
        $constName = 'SOME_CONST';
        SkipDynamicClassConstFetch::{$constName};
    }
}
