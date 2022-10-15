<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class SkipOnNew
{
    public function run()
    {
        $content = 'content';
        if (rand(0, 1)) {
            return $content;
        }
    }
}
