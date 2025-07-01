<?php

declare(strict_types=1);

namespace Rector\NodeAnalyzer;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Php\PhpPropertyReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Type;
use Rector\CodeQuality\ValueObject\DefinedPropertyWithType;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\Php80\NodeAnalyzer\PromotedPropertyResolver;
use Rector\PhpParser\AstResolver;
use Rector\StaticTypeMapper\Resolver\ClassNameFromObjectTypeResolver;

/**
 * Can be local property, parent property etc.
 */
final readonly class PropertyPresenceChecker
{
    public function __construct(
        private PromotedPropertyResolver $promotedPropertyResolver,
        private NodeNameResolver $nodeNameResolver,
        private ReflectionProvider $reflectionProvider,
        private AstResolver $astResolver,
    ) {
    }

    /**
     * Includes parent classes and traits
     */
    public function hasClassContextProperty(Class_ $class, DefinedPropertyWithType $definedPropertyWithType): bool
    {
        $propertyOrParam = $this->getClassContextProperty($class, $definedPropertyWithType);
        return $propertyOrParam !== null;
    }

    public function getClassContextProperty(
        Class_ $class,
        DefinedPropertyWithType $definedPropertyWithType
    ): Property | Param | null {
        $className = $this->nodeNameResolver->getName($class);
        if ($className === null) {
            return null;
        }

        $property = $class->getProperty($definedPropertyWithType->getPropertyName());
        if ($property instanceof Property) {
            return $property;
        }

        $property = $this->matchPropertyByParentNonPrivateProperties($className, $definedPropertyWithType);
        if ($property instanceof Property || $property instanceof Param) {
            return $property;
        }

        $promotedPropertyParams = $this->promotedPropertyResolver->resolveFromClass($class);

        foreach ($promotedPropertyParams as $promotedPropertyParam) {
            if ($this->nodeNameResolver->isName($promotedPropertyParam, $definedPropertyWithType->getPropertyName())) {
                return $promotedPropertyParam;
            }
        }

        return null;
    }

    /**
     * @return PhpPropertyReflection[]
     */
    private function getParentClassNonPrivatePropertyReflections(string $className): array
    {
        if (! $this->reflectionProvider->hasClass($className)) {
            return [];
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        $propertyReflections = [];

        foreach ($classReflection->getParents() as $parentClassReflection) {
            $propertyNames = $this->resolveNonPrivatePropertyNames($parentClassReflection);

            foreach ($propertyNames as $propertyName) {
                $propertyReflections[] = $parentClassReflection->getNativeProperty($propertyName);
            }
        }

        return $propertyReflections;
    }

    private function matchPropertyByType(
        DefinedPropertyWithType $definedPropertyWithType,
        PhpPropertyReflection $phpPropertyReflection
    ): Property | Param | null {
        if (! $definedPropertyWithType->getType() instanceof Type) {
            return null;
        }

        if (ClassNameFromObjectTypeResolver::resolve($definedPropertyWithType->getType()) === null) {
            return null;
        }

        if (ClassNameFromObjectTypeResolver::resolve($phpPropertyReflection->getWritableType()) === null) {
            return null;
        }

        $type = $definedPropertyWithType->getType();
        if (! $type->equals($phpPropertyReflection->getWritableType())) {
            return null;
        }

        return $this->astResolver->resolvePropertyFromPropertyReflection($phpPropertyReflection);
    }

    private function matchPropertyByParentNonPrivateProperties(
        string $className,
        DefinedPropertyWithType $definedPropertyWithType,
    ): Property | Param | null {
        $availablePropertyReflections = $this->getParentClassNonPrivatePropertyReflections($className);

        foreach ($availablePropertyReflections as $availablePropertyReflection) {
            // 1. match type by priority
            $property = $this->matchPropertyByType($definedPropertyWithType, $availablePropertyReflection);
            if ($property instanceof Property || $property instanceof Param) {
                return $property;
            }

            $nativePropertyReflection = $availablePropertyReflection->getNativeReflection();

            // 2. match by name
            if ($nativePropertyReflection->getName() === $propertyMetadata->getName()) {
                return $this->astResolver->resolvePropertyFromPropertyReflection($availablePropertyReflection);
            }
        }

        return null;
    }

    /**
     * @return string[]
     */
    private function resolveNonPrivatePropertyNames(ClassReflection $classReflection): array
    {
        $propertyNames = [];

        $nativeReflection = $classReflection->getNativeReflection();
        foreach ($nativeReflection->getProperties() as $reflectionProperty) {
            if ($reflectionProperty->isPrivate()) {
                continue;
            }

            $propertyNames[] = $reflectionProperty->getName();
        }

        return $propertyNames;
    }
}
