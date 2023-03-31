<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\NodeTypeResolver\Node\AttributeKey;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use ReflectionClass;

final class ClassAnalyzer
{
    public function __construct(private readonly ReflectionProvider $reflectionProvider)
    {
    }

    public function isAnonymousClass(Node|ReflectionClass $node): bool
    {
        if ($node instanceof ReflectionClass) {
            if ($node->isAnonymous()) {
                return true;
            }

            if (! $this->reflectionProvider->hasClass($node->getName())) {
                return false;
            }

            $classReflection = $this->reflectionProvider->getClass($node->getName());
            return $classReflection->isAnonymous();
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
