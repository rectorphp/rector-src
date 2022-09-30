<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class SkipStaticVariable
{
    public function run()
    {
        static $content;
        $content = 'test';
        return $content;
    }
}
