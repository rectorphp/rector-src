<?php

declare(strict_types=1);

namespace Rector\Core\NodeManipulator;

use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\FamilyTree\NodeAnalyzer\ClassChildAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;

final class ClassManipulator
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly ClassChildAnalyzer $classChildAnalyzer
    ) {
    }

    public function hasParentMethodOrInterface(ObjectType $objectType, string $oldMethod, string $newMethod): bool
    {
        if (! $this->reflectionProvider->hasClass($objectType->getClassName())) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($objectType->getClassName());
        $ancestorClassReflections = array_merge($classReflection->getParents(), $classReflection->getInterfaces());
        foreach ($ancestorClassReflections as $ancestorClassReflection) {
            if (! $ancestorClassReflection->hasMethod($oldMethod)) {
                continue;
            }

            if ($this->classChildAnalyzer->hasChildClassMethod($ancestorClassReflection, $newMethod)) {
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
