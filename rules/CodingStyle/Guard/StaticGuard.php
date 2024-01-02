<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Guard;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PHPStan\Reflection\MethodReflection;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Reflection\ReflectionResolver;

final readonly class StaticGuard
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private ReflectionResolver $reflectionResolver
    ) {
    }

    public function isLegal(Closure|ArrowFunction $node): bool
    {
        if ($node->static) {
            return false;
        }

        $nodes = $node instanceof Closure
            ? $node->stmts
            : [$node->expr];

        return ! (bool) $this->betterNodeFinder->findFirst(
            $nodes,
            function (Node $subNode): bool {
                if (! $subNode instanceof StaticCall) {
                    return $subNode instanceof Variable && $subNode->name === 'this';
                }

                $methodReflection = $this->reflectionResolver->resolveMethodReflectionFromStaticCall($subNode);
                if (! $methodReflection instanceof MethodReflection) {
                    return false;
                }

                return ! $methodReflection->isStatic();
            }
        );
    }
}
