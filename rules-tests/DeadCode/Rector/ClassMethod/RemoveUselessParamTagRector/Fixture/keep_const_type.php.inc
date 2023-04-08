<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

final class KeepConstantType
{
    /**
     * @param KeepConstantType::* $type
     */
    public function run(string $type)
    {
    }
}

