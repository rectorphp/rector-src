<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class SkipDynamicKey
{
    public function create($variable, $type, $data)
    {
        $someVar[sprintf('%', $type)] = 0.0;
        $someVar[$type] = 0.0;

        return $someVar;
    }
}