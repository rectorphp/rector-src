<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Php\PhpPropertyReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\TypeWithClassName;
use Rector\Core\PhpParser\AstResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\Php80\NodeAnalyzer\PromotedPropertyResolver;
use Rector\PostRector\ValueObject\PropertyMetadata;

/**
 * Can be local property, parent property etc.
 */
final class PropertyPresenceChecker
{
    public function __construct(
        private readonly PromotedPropertyResolver $promotedPropertyResolver,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly AstResolver $astResolver,
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

        $property = $this->matchPropertyByParentNonPrivateProperties($className, $propertyMetadata);
        if ($property instanceof Property || $property instanceof Param) {
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
        PropertyMetadata $propertyMetadata,
        PhpPropertyReflection $phpPropertyReflection
    ): Property | Param | null {
        if ($propertyMetadata->getType() === null) {
            return null;
        }

        if (! $propertyMetadata->getType() instanceof TypeWithClassName) {
            return null;
        }

        if (! $phpPropertyReflection->getWritableType() instanceof TypeWithClassName) {
            return null;
        }

        $propertyObjectType = $propertyMetadata->getType();
        if (! $propertyObjectType->equals($phpPropertyReflection->getWritableType())) {
            return null;
        }

        return $this->astResolver->resolvePropertyFromPropertyReflection($phpPropertyReflection);
    }

    private function matchPropertyByParentNonPrivateProperties(
        string $className,
        PropertyMetadata $propertyMetadata,
    ): Property | Param | null {
        $availablePropertyReflections = $this->getParentClassNonPrivatePropertyReflections($className);

        foreach ($availablePropertyReflections as $availablePropertyReflection) {
            // 1. match type by priority
            $property = $this->matchPropertyByType($propertyMetadata, $availablePropertyReflection);
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

        $reflectionClass = $classReflection->getNativeReflection();
        foreach ($reflectionClass->getProperties() as $property) {
            if ($property->isPrivate()) {
                continue;
            }

            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }
}
