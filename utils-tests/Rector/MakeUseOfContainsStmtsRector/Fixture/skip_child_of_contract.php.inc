<?php

namespace Rector\Utils\Tests\Rector\MakeUseOfContainsStmtsRector\Fixture;

use PhpParser\Node\Stmt\ClassMethod;

final class SkipChildOfContract
{
    public function process(ClassMethod $classMethod): void
    {
        $stmts = $classMethod->stmts;
    }
}
