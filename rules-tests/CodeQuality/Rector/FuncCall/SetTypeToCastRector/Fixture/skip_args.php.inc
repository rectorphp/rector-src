<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SetTypeToCastRector\Fixture;

final class SkipArgs
{
    public function run($foo)
    {
        is_bool(settype($foo, 'string'));

        settype($foo, settype($foo, 'string'));
    }
}
