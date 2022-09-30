<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class SkipOnVarVar
{
    public function run1()
    {
        $other   = 'content';
        $content = &$other;
        $content = 'foo';
        return $content;
    }

    public function run2()
    {
        $content = 'content';
        $other   = &$content;
        $content = 'foo';
        return $content;
    }
}
