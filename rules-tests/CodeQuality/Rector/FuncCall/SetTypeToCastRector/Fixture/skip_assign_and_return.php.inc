<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SetTypeToCastRector\Fixture;

final class SkipAssignAndrReturn
{
    public function run($foo)
    {
        // $result is always bool, as settype() returns true on success, false on failure
        $result = settype($foo, 'string');
    }

    public function retype($foo)
    {
        // $result is always bool, as settype() returns true on success, false on failure
        return settype($foo, 'string');
    }
}
