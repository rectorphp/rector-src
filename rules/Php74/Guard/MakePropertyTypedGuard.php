<?php

declare(strict_types=1);

namespace Rector\Php74\Guard;

use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\Core\NodeAnalyzer\PropertyAnalyzer;
use Rector\Core\NodeManipulator\PropertyManipulator;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Privatization\Guard\ParentPropertyLookupGuard;

final class MakePropertyTypedGuard
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly PropertyAnalyzer $propertyAnalyzer,
        private readonly PropertyManipulator $propertyManipulator,
        private readonly ParentPropertyLookupGuard $parentPropertyLookupGuard
    ) {
    }

    public function isLegal(Property $property, bool $inlinePublic = true): bool
    {
        if ($property->type !== null) {
            return false;
        }

        if (count($property->props) > 1) {
            return false;
        }

        $scope = $property->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return false;
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        /**
         * - trait properties are unpredictable based on class context they appear in
         * - on interface properties as well, as interface not allowed to have property
         */
        if (! $classReflection->isClass()) {
            return false;
        }

        $propertyName = $this->nodeNameResolver->getName($property);

        if ($this->propertyManipulator->isUsedByTrait($classReflection, $propertyName)) {
            return false;
        }

        if ($inlinePublic) {
            return ! $this->propertyAnalyzer->hasForbiddenType($property);
        }

        if ($property->isPrivate()) {
            return ! $this->propertyAnalyzer->hasForbiddenType($property);
        }

        return $this->isSafeProtectedProperty($property, $classReflection);
    }

    private function isSafeProtectedProperty(Property $property, ClassReflection $class): bool
    {
        if (! $property->isProtected()) {
            return false;
        }

        if (! $class->isFinal()) {
            return false;
        }

        return $this->parentPropertyLookupGuard->isLegal($property);
    }
}
