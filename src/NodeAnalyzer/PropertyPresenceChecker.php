<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\Core\PhpParser\AstResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\Php80\NodeAnalyzer\PromotedPropertyResolver;
use Rector\PostRector\ValueObject\PropertyMetadata;
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
    public function hasClassContextProperty(Class_ $class, PropertyMetadata $propertyMetadata): bool
    {
        $propertyOrParam = $this->getClassContextProperty($class, $propertyMetadata);
        return $propertyOrParam !== null;
    }

    public function getClassContextProperty(Class_ $class, PropertyMetadata $propertyMetadata): Property | Param | null
    {
        $className = $this->nodeNameResolver->getName($class);
        if ($className === null) {
            return null;
        }

        if (! $this->reflectionProvider->hasClass($className)) {
            return null;
        }

        $property = $class->getProperty($propertyMetadata->getName());
        if ($property instanceof Property) {
            return $property;
        }

        $property = $this->matchPropertyByParentPublicOrProtectedProperties($className, $propertyMetadata);
        if ($property instanceof Property) {
            return $property;
        }

        $promotedPropertyParams = $this->promotedPropertyResolver->resolveFromClass($class);
        foreach ($promotedPropertyParams as $promotedPropertyParam) {
            if ($this->nodeNameResolver->isName($promotedPropertyParam, $propertyMetadata->getName())) {
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

    private function matchPropertyByType(
        PropertyMetadata $propertyMetadata,
        ReflectionProperty $reflectionProperty
    ): ?Property {
        if ($propertyMetadata->getType() === null) {
            return null;
        }

        if (! $reflectionProperty->getType() instanceof \ReflectionNamedType) {
            return null;
        }

        $propertyReflectionObjectType = new ObjectType((string) $reflectionProperty->getType());
        if (! $propertyReflectionObjectType->isSuperTypeOf($propertyMetadata->getType())->yes()) {
            return null;
        }

        return $this->astResolver->resolvePropertyFromPropertyReflection($reflectionProperty);
    }

    private function matchPropertyByParentPublicOrProtectedProperties(
        string $className,
        PropertyMetadata $propertyMetadata
    ): ?Property {
        $availablePropertyReflections = $this->getParentClassPublicAndProtectedPropertyReflections($className);

        foreach ($availablePropertyReflections as $availablePropertyReflection) {
            // 1. match type by priority
            $property = $this->matchPropertyByType($propertyMetadata, $availablePropertyReflection);
            if ($property instanceof Property) {
                return $property;
            }

            // 2. match by name
            if ($availablePropertyReflection->getName() === $propertyMetadata->getName()) {
                return $this->astResolver->resolvePropertyFromPropertyReflection($availablePropertyReflection);
            }
        }

        return null;
    }
}
