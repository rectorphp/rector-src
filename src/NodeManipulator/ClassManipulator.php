<?php

declare(strict_types=1);

namespace Rector\Core\NodeManipulator;

use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PostRector\Collector\NodesToRemoveCollector;

final class ClassManipulator
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private NodesToRemoveCollector $nodesToRemoveCollector,
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function hasParentMethodOrInterface(ObjectType $objectType, string $methodName): bool
    {
        if (! $this->reflectionProvider->hasClass($objectType->getClassName())) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($objectType->getClassName());
        foreach ($classReflection->getAncestors() as $ancestorClassReflection) {
            if ($classReflection === $ancestorClassReflection) {
                continue;
            }

            if ($ancestorClassReflection->hasMethod($methodName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string[]
     */
    public function getPrivatePropertyNames(Class_ $class): array
    {
        $privateProperties = array_filter(
            $class->getProperties(),
            fn (Property $property): bool => $property->isPrivate()
        );

        return $this->nodeNameResolver->getNames($privateProperties);
    }

    /**
     * @return string[]
     */
    public function getImplementedInterfaceNames(Class_ $class): array
    {
        return $this->nodeNameResolver->getNames($class->implements);
    }

    public function hasInterface(Class_ $class, ObjectType $interfaceObjectType): bool
    {
        return $this->nodeNameResolver->isName($class->implements, $interfaceObjectType->getClassName());
    }

    public function hasTrait(Class_ $class, string $desiredTrait): bool
    {
        foreach ($class->getTraitUses() as $traitUse) {
            foreach ($traitUse->traits as $traitName) {
                if (! $this->nodeNameResolver->isName($traitName, $desiredTrait)) {
                    continue;
                }

                return true;
            }
        }

        return false;
    }

    public function replaceTrait(Class_ $class, string $oldTrait, string $newTrait): void
    {
        foreach ($class->getTraitUses() as $traitUse) {
            foreach ($traitUse->traits as $key => $traitTrait) {
                if (! $this->nodeNameResolver->isName($traitTrait, $oldTrait)) {
                    continue;
                }

                $traitUse->traits[$key] = new FullyQualified($newTrait);
                break;
            }
        }
    }

    /**
     * @return string[]
     */
    public function getClassLikeNodeParentInterfaceNames(Class_ | Interface_ $classLike): array
    {
        if ($classLike instanceof Class_) {
            return $this->nodeNameResolver->getNames($classLike->implements);
        }

        return $this->nodeNameResolver->getNames($classLike->extends);
    }

    public function removeInterface(Class_ $class, string $desiredInterface): void
    {
        foreach ($class->implements as $implement) {
            if (! $this->nodeNameResolver->isName($implement, $desiredInterface)) {
                continue;
            }

            $this->nodesToRemoveCollector->addNodeToRemove($implement);
        }
    }
}
