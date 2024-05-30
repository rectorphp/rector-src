<?php

declare(strict_types=1);

namespace Rector\NodeManipulator;

use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\NodeNameResolver\NodeNameResolver;

final readonly class ClassManipulator
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private ReflectionProvider $reflectionProvider,
    ) {
    }

    public function hasParentMethodOrInterface(ObjectType $objectType, string $oldMethod): bool
    {
        if (! $this->reflectionProvider->hasClass($objectType->getClassName())) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($objectType->getClassName());
        $ancestorClassReflections = [...$classReflection->getParents(), ...$classReflection->getInterfaces()];
        foreach ($ancestorClassReflections as $ancestorClassReflection) {
            if (! $ancestorClassReflection->hasMethod($oldMethod)) {
                continue;
            }

            return true;
        }

        return false;
    }

    /**
     * @api phpunit
     */
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
}
