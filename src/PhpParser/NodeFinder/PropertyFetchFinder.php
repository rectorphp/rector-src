<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\NodeFinder;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class PropertyFetchFinder
{
    /**
     * @var string
     */
    private const THIS = 'this';

    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private NodeNameResolver $nodeNameResolver,
        private ReflectionProvider $reflectionProvider,
        private AstResolver $astResolver,
        private ClassAnalyzer $classAnalyzer
    ) {
    }

    /**
     * @return PropertyFetch[]|StaticPropertyFetch[]
     */
    public function findPrivatePropertyFetches(Property | Param $propertyOrPromotedParam): array
    {
        $classLike = $propertyOrPromotedParam->getAttribute(AttributeKey::CLASS_NODE);
        if (! $classLike instanceof Class_) {
            return [];
        }

        $propertyName = $this->resolvePropertyName($propertyOrPromotedParam);
        if ($propertyName === null) {
            return [];
        }

        $className = (string) $this->nodeNameResolver->getName($classLike);
        if (! $this->reflectionProvider->hasClass($className)) {
            /** @var PropertyFetch[]|StaticPropertyFetch[] $propertyFetches */
            $propertyFetches = $this->findPropertyFetchesInClassLike($classLike->stmts, $propertyName);
            return $propertyFetches;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        $nodes = [$classLike];
        $nodes = array_merge($nodes, $this->astResolver->parseClassReflectionTraits($classReflection));

        return $this->findPropertyFetchesInNonAnonymousClassLike($nodes, $propertyName);
    }

    /**
     * @return PropertyFetch[]|StaticPropertyFetch[]
     */
    public function findLocalPropertyFetchesByName(Class_ $class, string $paramName): array
    {
        /** @var PropertyFetch[]|StaticPropertyFetch[] $propertyFetches */
        $propertyFetches = $this->betterNodeFinder->findInstancesOf(
            $class,
            [PropertyFetch::class, StaticPropertyFetch::class]
        );

        $foundPropertyFetches = [];

        foreach ($propertyFetches as $propertyFetch) {
            if ($propertyFetch instanceof PropertyFetch && ! $this->nodeNameResolver->isName(
                $propertyFetch->var,
                self::THIS
            )) {
                continue;
            }

            if ($propertyFetch instanceof StaticPropertyFetch && ! $this->nodeNameResolver->isName(
                $propertyFetch->class,
                'self'
            )) {
                continue;
            }

            if (! $this->nodeNameResolver->isName($propertyFetch->name, $paramName)) {
                continue;
            }

            $foundPropertyFetches[] = $propertyFetch;
        }

        return $foundPropertyFetches;
    }

    /**
     * @param Stmt[] $nodes
     * @return PropertyFetch[]|StaticPropertyFetch[]
     */
    private function findPropertyFetchesInNonAnonymousClassLike(array $nodes, string $propertyName): array
    {
        /** @var PropertyFetch[]|StaticPropertyFetch[] $propertyFetches */
        $propertyFetches = $this->findPropertyFetchesInClassLike($nodes, $propertyName);

        foreach ($propertyFetches as $key => $propertyFetch) {
            $currentClassLike = $propertyFetch->getAttribute(AttributeKey::CLASS_NODE);
            
            if (! $currentClassLike instanceof \PhpParser\Node\Stmt\ClassLike) {
                continue;
            }
            
            if ($this->classAnalyzer->isAnonymousClass($currentClassLike)) {
                unset($propertyFetches[$key]);
            }
        }

        return $propertyFetches;
    }

    /**
     * @param Stmt[] $nodes
     * @return PropertyFetch[]|StaticPropertyFetch[]
     */
    private function findPropertyFetchesInClassLike(array $nodes, string $propertyName): array
    {
        /** @var PropertyFetch[]|StaticPropertyFetch[] $propertyFetches */
        $propertyFetches = $this->betterNodeFinder->find($nodes, function (Node $node) use ($propertyName): bool {
            // property + static fetch
            if ($node instanceof PropertyFetch && $this->nodeNameResolver->isName($node->var, self::THIS)) {
                return $this->nodeNameResolver->isName($node, $propertyName);
            }

            if (! $node instanceof StaticPropertyFetch) {
                return false;
            }

            if (! $this->nodeNameResolver->isNames($node->class, ['self', self::THIS, 'static'])) {
                return false;
            }

            return $this->nodeNameResolver->isName($node, $propertyName);
        });

        return $propertyFetches;
    }

    private function resolvePropertyName(Property | Param $propertyOrPromotedParam): ?string
    {
        if ($propertyOrPromotedParam instanceof Property) {
            return $this->nodeNameResolver->getName($propertyOrPromotedParam->props[0]);
        }

        return $this->nodeNameResolver->getName($propertyOrPromotedParam->var);
    }
}
