<?php

namespace Rector\Tests\Php85\Rector\ShellExec\ShellExecFunctionCallOverBackticksRector\Fixture;

class SkipEmptyBackticks
{
    public function run()
    {
        ``;
    }
}
