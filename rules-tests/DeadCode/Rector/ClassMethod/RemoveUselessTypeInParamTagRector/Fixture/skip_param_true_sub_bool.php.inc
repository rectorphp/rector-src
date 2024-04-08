<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

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

