<?php

namespace Rector\Tests\CodingStyle\Rector\Stmt\NewlineAfterStatementRector\Fixture;

final class SkipInsideDoWhileNextNotNode
{
    public function run($foo)
    {
        do {
            if ($foo) {

            }
        } while ($this->foo);
    }
}
