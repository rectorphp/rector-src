<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class ClassAnalyzer
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    public function isAnonymousClass(Node|ClassReflection $node): bool
    {
        if ($node instanceof ClassReflection) {
            return $node->isAnonymous();
        }

        if (! $node instanceof Class_) {
            return false;
        }

        if ($node->isAnonymous()) {
            return true;
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return false;
        }

        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection) {
            return $classReflection->isAnonymous();
        }

        return false;
    }
}
