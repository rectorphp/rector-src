<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class SkipCallByRef
{
    public function run(&$content)
    {
        $content = null;
        return $content;
    }
}

