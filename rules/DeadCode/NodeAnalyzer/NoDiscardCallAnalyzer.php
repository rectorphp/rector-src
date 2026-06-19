<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PHPStan\Reflection\AttributeReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PHPStan\ScopeFetcher;

final readonly class NoDiscardCallAnalyzer
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private NodeTypeResolver $nodeTypeResolver,
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function isNoDiscardCall(Expr $expr): bool
    {
        if ($expr instanceof FuncCall) {
            $name = $this->nodeNameResolver->getName($expr);
            if ($name === null) {
                return false;
            }

            $scope = ScopeFetcher::fetch($expr);
            $functionName = new Name($name);

            if (! $this->reflectionProvider->hasFunction($functionName, $scope)) {
                return false;
            }

            return $this->hasNoDiscardAttribute(
                $this->reflectionProvider->getFunction($functionName, $scope)
                    ->getAttributes()
            );
        }

        if ($expr instanceof StaticCall) {
            $classNames = $this->nodeTypeResolver->getType($expr->class)
                ->getObjectClassNames();
            $methodName = $this->nodeNameResolver->getName($expr->name);
        } elseif ($expr instanceof MethodCall || $expr instanceof NullsafeMethodCall) {
            $classNames = $this->nodeTypeResolver->getType($expr->var)
                ->getObjectClassNames();
            $methodName = $this->nodeNameResolver->getName($expr->name);
        } else {
            return false;
        }

        if ($classNames === [] || $methodName === null) {
            return false;
        }

        foreach ($classNames as $className) {
            if (! $this->reflectionProvider->hasClass($className)) {
                continue;
            }

            $classReflection = $this->reflectionProvider->getClass($className);
            if (! $classReflection->hasNativeMethod($methodName)) {
                continue;
            }

            if ($this->hasNoDiscardAttribute($classReflection->getNativeMethod($methodName)->getAttributes())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param AttributeReflection[] $attributes
     */
    private function hasNoDiscardAttribute(array $attributes): bool
    {
        return array_any(
            $attributes,
            fn (AttributeReflection $attributeReflection): bool => $attributeReflection->getName() === 'NoDiscard'
        );
    }
}
