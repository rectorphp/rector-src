<?php

declare(strict_types=1);

namespace Rector\Privatization\VisibilityGuard;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Class_;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpParser\AstResolver;
use Rector\PhpParser\Node\BetterNodeFinder;

final class ParentClassMagicCallGuard
{
    /**
     * @var array<string, bool>
     */
    private array $cachedContainsByClassName = [];

    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly AstResolver $astResolver,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    /**
     * E.g. parent class has $this->{$magicName} call that might call the protected method
     * If we make it private, it will break the code
     */
    public function containsParentClassMagicCall(Class_ $class): bool
    {
        // cache as heavy AST parsing here
        $className = $this->nodeNameResolver->getName($class);
        if (isset($this->cachedContainsByClassName[$className])) {
            return $this->cachedContainsByClassName[$className];
        }

        if ($class->extends === null) {
            return false;
        }

        $parentClassName = $this->nodeNameResolver->getName($class->extends);

        $parentClass = $this->astResolver->resolveClassFromName($parentClassName);
        if (! $parentClass instanceof Class_) {
            $this->cachedContainsByClassName[$className] = false;
            return false;
        }

        foreach ($parentClass->getMethods() as $classMethod) {
            if ($classMethod->isAbstract()) {
                continue;
            }

            /** @var MethodCall[] $methodCalls */
            $methodCalls = $this->betterNodeFinder->findInstancesOfScoped(
                (array) $classMethod->stmts,
                MethodCall::class
            );
            foreach ($methodCalls as $methodCall) {
                if ($methodCall->name instanceof Expr) {
                    $this->cachedContainsByClassName[$className] = true;
                    return true;
                }
            }
        }

        return $this->containsParentClassMagicCall($parentClass);
    }
}
