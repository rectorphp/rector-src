<?php

declare(strict_types=1);

namespace Rector\Php70\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;

final class MethodCallNameAnalyzer
{
    public function isLocalMethodCallNamed(Expr $expr, string $desiredMethodName): bool
    {
        if (! $expr instanceof MethodCall) {
            return false;
        }

        if (! $expr->var instanceof Expr\Variable) {
            return false;
        }

        if ($expr->var->name !== 'this') {
            return false;
        }

        if (! $expr->name instanceof Identifier) {
            return false;
        }

        return $expr->name->toString() === $desiredMethodName;
    }

    public function isParentMethodCall(Class_ $class, Expr $expr): bool
    {
        if (! $class->extends instanceof Name) {
            return false;
        }

        $parentClassName = $class->extends->toString();
        if ($class->getMethod($parentClassName)) {
            return false;
        }

        return $this->isLocalMethodCallNamed($expr, $parentClassName);
    }
}
