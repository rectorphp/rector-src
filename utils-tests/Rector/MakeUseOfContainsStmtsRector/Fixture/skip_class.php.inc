<?php

namespace Rector\Utils\Tests\Rector\MakeUseOfContainsStmtsRector\Fixture;

use PhpParser\Node\Stmt\Class_;

final class SkipClass
{
    public function process(Class_ $class): void
    {
        $stmts = $class->stmts;
    }
}
