<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class SkipOnMethodCall
{
    public function run(SkipOnMethodCall $skipOnMethodCall)
    {
        $content = $skipOnMethodCall->run($skipOnMethodCall);
        return $content;
    }
}
