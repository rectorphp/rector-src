<?php

namespace Rector\Tests\CodeQuality\Rector\StmtsAwareInterface\MoveInnerFunctionToTopLevelRector\Fixture;

function already_top_level(): void
{
    echo 'hello';
}

function caller(): void
{
    already_top_level();
}
