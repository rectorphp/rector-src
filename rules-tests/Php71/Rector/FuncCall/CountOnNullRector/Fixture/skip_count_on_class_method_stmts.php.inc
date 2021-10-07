<?php

namespace Rector\Tests\Php71\Rector\FuncCall\CountOnNullRector\Fixture;

use PhpParser\Node\Stmt\ClassMethod;

final class SkipCountOnClassMethodStmts
{
    public function run(ClassMethod $classMethod)
    {
        return count($classMethod->stmts) === 5;
    }
}
