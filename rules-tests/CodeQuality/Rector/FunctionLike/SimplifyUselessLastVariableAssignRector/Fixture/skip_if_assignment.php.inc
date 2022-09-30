<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class SkipIfAssignment
{
    public function run()
    {
        if ($content = 'test') {
            echo '';
        }

        return $content;
    }
}
