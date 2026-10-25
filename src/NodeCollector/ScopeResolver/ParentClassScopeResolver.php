<?php

declare(strict_types=1);

namespace Rector\NodeCollector\ScopeResolver;

use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;

final class ParentClassScopeResolver
{
    public function resolveParentClassReflection(Scope $scope): ?ClassReflection
    {
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        return $classReflection->getParentClass();
    }
}
