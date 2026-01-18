<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\NodeNameResolver\NodeNameResolver;

final readonly class ParentClassAnalyzer
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
    ) {

    }

    public function hasParentCall(ClassMethod $classMethod): bool
    {
        if ($classMethod->isAbstract()) {
            return false;
        }

        if ($classMethod->isPrivate()) {
            return false;
        }

        $classMethodName = $classMethod->name->name;

        foreach ((array) $classMethod->stmts as $stmt) {
            if (! $stmt instanceof Expression) {
                continue;
            }

            $expr = $stmt->expr;
            if (! $expr instanceof StaticCall) {
                continue;
            }

            if (! $this->nodeNameResolver->isName($expr->class, 'parent')) {
                continue;
            }

            if ($this->nodeNameResolver->isName($expr->name, $classMethodName)) {
                return true;
            }
        }

        return false;
    }
}
