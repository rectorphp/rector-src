<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\Php80\NodeAnalyzer\PromotedPropertyResolver;
use ReflectionProperty;

final class PropertyPresenceChecker
{
    public function __construct(
        private PromotedPropertyResolver $promotedPropertyResolver,
        private NodeNameResolver $nodeNameResolver,
        private ReflectionProvider $reflectionProvider
    ) {
    }

    /**
     * Includes parent classes and traits
     */
    public function hasClassContextPropertyByName(Class_ $class, string $propertyName): bool
    {
        $className = $this->nodeNameResolver->getName($class);
        if ($className === null) {
            return false;
        }

        if (! $this->reflectionProvider->hasClass($className)) {
            return false;
        }

        $property = $class->getProperty($propertyName);
        if ($property instanceof Property) {
            return true;
        }

        $availablePropertyReflections = $this->getParentClassPublicAndProtectedPropertyReflections($className);

        foreach ($availablePropertyReflections as $availablePropertyReflection) {
            if ($availablePropertyReflection->getName() !== $propertyName) {
                continue;
            }

            return true;
        }

        $promotedPropertyParams = $this->promotedPropertyResolver->resolveFromClass($class);
        foreach ($promotedPropertyParams as $promotedPropertyParam) {
            if ($this->nodeNameResolver->isName($promotedPropertyParam, $propertyName)) {
                return true;
            }
        }

        return false;
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

            $propertyReflections = array_merge($propertyReflections, $currentPropertyReflections);
        }

        return $propertyReflections;
    }
}
