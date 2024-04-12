<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PHPStan\Reflection\ClassReflection;
use Rector\NodeAnalyzer\ExprAnalyzer;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Reflection\ReflectionResolver;

final readonly class SafeLeftTypeBooleanAndOrAnalyzer
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ExprAnalyzer $exprAnalyzer,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function isSafe(BooleanAnd|BooleanOr $booleanAnd): bool
    {
        $hasNonTypedFromParam = (bool) $this->betterNodeFinder->findFirst(
            $booleanAnd->left,
            fn (Node $node): bool => $node instanceof Variable && $this->exprAnalyzer->isNonTypedFromParam($node)
        );

        if ($hasNonTypedFromParam) {
            return false;
        }

        $hasPropertyFetchOrArrayDimFetch = (bool) $this->betterNodeFinder->findFirst(
            $booleanAnd->left,
            static fn (Node $node): bool => $node instanceof PropertyFetch || $node instanceof StaticPropertyFetch || $node instanceof ArrayDimFetch
        );

        // get type from Property and ArrayDimFetch is unreliable
        if ($hasPropertyFetchOrArrayDimFetch) {
            return false;
        }

        // skip trait this
        $classReflection = $this->reflectionResolver->resolveClassReflection($booleanAnd);
        if ($classReflection instanceof ClassReflection && $classReflection->isTrait()) {
            return ! (bool) $this->betterNodeFinder->findFirst(
                $booleanAnd->left,
                static fn(Node $node): bool => $node instanceof Instanceof_
            );
        }

        return true;
    }
}
