<?php

declare(strict_types=1);

namespace Rector\DependencyInjection\NodeAnalyzer;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\Core\PhpParser\Node\BetterNodeFinder;

final class ControllerClassMethodAnalyzer
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder
    ) {
    }

    public function isInControllerActionMethod(Variable $variable, Scope $scope): bool
    {
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        $className = $classReflection->getName();
        if (! \str_ends_with($className, 'Controller')) {
            return false;
        }

        $classMethod = $this->betterNodeFinder->findParentType($variable, ClassMethod::class);
        if (! $classMethod instanceof ClassMethod) {
            return false;
        }

        // is probably in controller action
        return $classMethod->isPublic();
    }
}
