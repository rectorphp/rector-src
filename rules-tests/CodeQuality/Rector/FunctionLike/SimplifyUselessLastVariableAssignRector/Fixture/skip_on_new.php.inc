<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class SkipOnNew
{
    public function run()
    {
        $content = new \stdClass();
        return $content;
    }
}
