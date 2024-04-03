<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

class SkipParamTrueSubBool
{
    /**
     * @param true $param
     */
    public function run(bool $param): void
    {
		echo 'test';
    }
}

