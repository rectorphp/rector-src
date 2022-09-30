<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class SkipOnVarVar
{
    public function run()
    {
        $other   = 'content';
        $content = 1;
        $$other  = 'foo';
        return $content;
    }
}
