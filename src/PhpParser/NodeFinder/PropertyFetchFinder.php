<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\NodeFinder;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\SmartFileSystem\SmartFileSystem;
use PhpParser\Parser;

final class PropertyFetchFinder
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private NodeNameResolver $nodeNameResolver,
        private ReflectionProvider $reflectionProvider,
        private ReflectionResolver $reflectionResolver,
        private SmartFileSystem $smartFileSystem,
        private Parser $parser
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

        $className       = $this->nodeNameResolver->getName($classLike);
        $classReflection = $this->reflectionProvider->getClass($className);

        if (! $classReflection instanceof ClassReflection) {
            return [];
        }

        $traits = $classReflection->getTraits(true);
        $propertyFetches = [];
        foreach ($traits as $trait) {
            $fileName = $trait->getFileName();
            if (! $fileName) {
                continue;
            }

            $fileContent = $this->smartFileSystem->readFile($fileName);
            $nodes       = $this->parser->parse($fileContent);

            $propertyFetches[] = $this->betterNodeFinder->findFirst($nodes, function (Node $node) use (
                $propertyName
            ): bool {
                // property + static fetch
                if (! $node instanceof PropertyFetch && ! $node instanceof StaticPropertyFetch) {
                    return false;
                }

                return $this->nodeNameResolver->isName($node, $propertyName);
            });
        }

        return $propertyFetches;

        dump_node($traits);die;

        $traits = [];
        foreach ($classLike->getTraitUses() as $traitUse) {
            foreach ($traitUse->traits as $trait) {
                $traitName = $this->nodeNameResolver->getName($trait);
                $traits[]  = $this->reflectionProvider->getClass($traitName);
            }
        }

        foreach ($traits as $trait) {

        }

        $classLikesToSearch = $this->nodeRepository->findUsedTraitsInClass($classLike);
        $classLikesToSearch[] = $classLike;

        $propertyName = $this->resolvePropertyName($propertyOrPromotedParam);
        if ($propertyName === null) {
            return [];
        }

        /** @var PropertyFetch[]|StaticPropertyFetch[] $propertyFetches */
        $propertyFetches = $this->betterNodeFinder->find($classLikesToSearch, function (Node $node) use (
            $propertyName,
            $classLikesToSearch
        ): bool {
            // property + static fetch
            if (! $node instanceof PropertyFetch && ! $node instanceof StaticPropertyFetch) {
                return false;
            }

            // is it the name match?
            if (! $this->nodeNameResolver->isName($node, $propertyName)) {
                return false;
            }

            $currentClassLike = $node->getAttribute(AttributeKey::CLASS_NODE);
            return in_array($currentClassLike, $classLikesToSearch, true);
        });

        return $propertyFetches;
    }

    /**
     * @return PropertyFetch[]
     */
    public function findLocalPropertyFetchesByName(Class_ $class, string $paramName): array
    {
        /** @var PropertyFetch[] $propertyFetches */
        $propertyFetches = $this->betterNodeFinder->findInstanceOf($class, PropertyFetch::class);

        $foundPropertyFetches = [];

        foreach ($propertyFetches as $propertyFetch) {
            if (! $this->nodeNameResolver->isName($propertyFetch->var, 'this')) {
                continue;
            }

            if (! $this->nodeNameResolver->isName($propertyFetch->name, $paramName)) {
                continue;
            }

            $foundPropertyFetches[] = $propertyFetch;
        }

        return $foundPropertyFetches;
    }

    private function resolvePropertyName(Property | Param $propertyOrPromotedParam): ?string
    {
        if ($propertyOrPromotedParam instanceof Property) {
            return $this->nodeNameResolver->getName($propertyOrPromotedParam->props[0]);
        }

        return $this->nodeNameResolver->getName($propertyOrPromotedParam->var);
    }
}
