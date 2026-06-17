<?php

namespace Rector\Tests\CodeQuality\Rector\StmtsAwareInterface\MoveInnerFunctionToTopLevelRector\Fixture;

function existing(): void
{
}

function outer_with_collision(): void
{
    function existing(): void
    {
        echo 'inner';
    }

    existing();
}
