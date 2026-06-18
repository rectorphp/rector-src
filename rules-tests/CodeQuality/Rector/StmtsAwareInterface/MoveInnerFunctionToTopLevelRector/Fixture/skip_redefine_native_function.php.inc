<?php

namespace Rector\Tests\CodeQuality\Rector\StmtsAwareInterface\MoveInnerFunctionToTopLevelRector\Fixture;

function SkipRedefineNativeFunction(): void
{
    function strlen(): int
    {
        return 1;
    }

    echo strlen();
}

function bar()
{
    echo strlen('abc');
}

bar();
