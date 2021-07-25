<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\PhpParser\AstResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\Php80\NodeAnalyzer\PromotedPropertyResolver;
use ReflectionProperty;

/**
 * Can be local property, parent property etc.
 */
final class PropertyPresenceChecker
{
    public function __construct(
        private PromotedPropertyResolver $promotedPropertyResolver,
        private NodeNameResolver $nodeNameResolver,
        private ReflectionProvider $reflectionProvider,
        private AstResolver $astResolver
    ) {
    }

    /**
     * Includes parent classes and traits
     */
    public function hasClassContextPropertyByName(Class_ $class, string $propertyName): bool
    {
        $propertyOrParam = $this->getClassContextPropertyByName($class, $propertyName);
        return $propertyOrParam !== null;
    }

    public function getClassContextPropertyByName(Class_ $class, string $propertyName): Property | Param | null
    {
        $className = $this->nodeNameResolver->getName($class);
        if ($className === null) {
            return null;
        }

        if (! $this->reflectionProvider->hasClass($className)) {
            return null;
        }

        $property = $class->getProperty($propertyName);
        if ($property instanceof Property) {
            return $property;
        }

        $availablePropertyReflections = $this->getParentClassPublicAndProtectedPropertyReflections($className);

        foreach ($availablePropertyReflections as $availablePropertyReflection) {
            if ($availablePropertyReflection->getName() !== $propertyName) {
                continue;
            }

            return $this->astResolver->resolvePropertyFromPropertyReflection($availablePropertyReflection);
        }

        $promotedPropertyParams = $this->promotedPropertyResolver->resolveFromClass($class);
        foreach ($promotedPropertyParams as $promotedPropertyParam) {
            if ($this->nodeNameResolver->isName($promotedPropertyParam, $propertyName)) {
                return $promotedPropertyParam;
            }
        }

        return null;
    }

    /**
     * @return ReflectionProperty[]
     */
    private function getParentClassPublicAndProtectedPropertyReflections(string $className): array
    {
        if (! $this->reflectionProvider->hasClass($className)) {
            return [];
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        $propertyReflections = [];
        foreach ($classReflection->getParents() as $parentClassReflection) {
            $nativeReflectionClass = $parentClassReflection->getNativeReflection();

            $currentPropertyReflections = $nativeReflectionClass->getProperties(
                ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED
            );

            $propertyReflections = [...$propertyReflections, ...$currentPropertyReflections];
        }

        return $propertyReflections;
    }
}
