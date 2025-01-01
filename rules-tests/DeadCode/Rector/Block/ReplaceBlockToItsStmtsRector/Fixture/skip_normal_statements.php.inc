<?php

namespace Rector\Tests\DeadCode\Rector\Block\ReplaceBlockToItsStmtsRector\Fixture;

function skipNormalStatements()
{
    echo "statement 1";
    echo PHP_EOL;
    echo "statement 2";
}
