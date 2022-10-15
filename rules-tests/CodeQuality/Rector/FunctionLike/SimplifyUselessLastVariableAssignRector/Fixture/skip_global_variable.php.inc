<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class SkipGlobalVariable
{
    public function clear()
    {
        global $conn;
        $conn = null;
        return $conn;
    }
}
