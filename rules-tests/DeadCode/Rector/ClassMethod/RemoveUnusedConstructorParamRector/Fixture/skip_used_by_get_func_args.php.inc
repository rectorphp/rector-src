<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector\Fixture;

final class SkipUsedByGetFuncArgs
{
    public $data;

    public function __construct($hey, $man)
    {
        $this->data = func_get_args();
    }
}
