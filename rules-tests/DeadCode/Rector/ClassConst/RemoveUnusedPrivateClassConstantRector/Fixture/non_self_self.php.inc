<?php

namespace Rector\Tests\DeadCode\Rector\ClassConst\RemoveUnusedPrivateClassConstantRector\Fixture;

final class DemoFile
{
    private const SOME_CONST = 1;
    
    public function run()
    {
        return DemoFile::SOME_CONST;
    }
}

?>
