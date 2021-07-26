<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class ClassAnalyzer
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function isAnonymousClass(Node $node): bool
    {
        if (! $node instanceof Class_) {
            return false;
        }

        /** @var string $className */
        $className = $this->nodeNameResolver->getName($node);
        return ! $this->reflectionProvider->hasClass($className);
    }

    public function isAnonymousClassOfClassMethod(ClassMethod $classMethod): bool
    {
        $class = $classMethod->getAttribute(AttributeKey::CLASS_NODE);
        if (! $class instanceof Class_) {
            return false;
        }

        return $this->isAnonymousClass($class);
    }
}
