<?php

declare(strict_types=1);

namespace Rector\ReadWrite\ReadNodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use Rector\Core\PhpParser\ClassLikeAstResolver;
use Rector\Core\PhpParser\NodeFinder\PropertyFetchFinder;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\ReadWrite\Contract\ReadNodeAnalyzerInterface;

/**
 * @implements ReadNodeAnalyzerInterface<PropertyFetch|StaticPropertyFetch>
 */
final class LocalPropertyFetchReadNodeAnalyzer implements ReadNodeAnalyzerInterface
{
    public function __construct(
        private readonly JustReadExprAnalyzer $justReadExprAnalyzer,
        private readonly PropertyFetchFinder $propertyFetchFinder,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly ClassLikeAstResolver $classLikeAstResolver
    ) {
    }

    public function supports(Expr $expr): bool
    {
        return $expr instanceof PropertyFetch || $expr instanceof StaticPropertyFetch;
    }

    public function isRead(Expr $expr): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($expr);
        if (! $classReflection instanceof ClassReflection || ! $classReflection->isClass()) {
            // assume worse to keep node protected
            return true;
        }

        $propertyName = $this->nodeNameResolver->getName($expr->name);
        if ($propertyName === null) {
            // assume worse to keep node protected
            return true;
        }

        /** @var Class_ $class */
        $class = $this->classLikeAstResolver->resolveClassFromClassReflection($classReflection);
        $propertyFetches = $this->propertyFetchFinder->findLocalPropertyFetchesByName($class, $propertyName);

        foreach ($propertyFetches as $propertyFetch) {
            if ($this->justReadExprAnalyzer->isReadContext($propertyFetch)) {
                return true;
            }
        }

        return false;
    }
}
