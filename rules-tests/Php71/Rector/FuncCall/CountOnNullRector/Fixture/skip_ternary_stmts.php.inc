<?php

namespace Rector\Tests\Php71\Rector\FuncCall\CountOnNullRector\Fixture;

use PhpParser\Node\Stmt\ClassMethod;

final class SkipTernaryStmts
{
    public function number(ClassMethod $classMethod): int
    {
        return $classMethod->stmts === null ? 0 : count($classMethod->stmts);
    }
}
