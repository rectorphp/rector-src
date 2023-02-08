<?php

declare(strict_types = 1);

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source;

use Exception;
use PhpParser\Node\Stmt\ClassLike;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeNameResolver\NodeNameResolver;

final class EnforceExceptionSuffixCallback
{
    public function __invoke(
        ClassLike $class,
        NodeNameResolver $nodeNameResolver,
        ReflectionProvider $reflectionProvider
    ): ?string {
        $fullyQualifiedClassName = (string) $nodeNameResolver->getName($class);
        $classReflection = $reflectionProvider->getClass($fullyQualifiedClassName);
        if (! $classReflection->isSubclassOf(Exception::class)) {
            return null;
        }

        if (!str_ends_with($fullyQualifiedClassName, 'Exception')) {
            return $fullyQualifiedClassName . 'Exception';
        }

        return null;
    }
}
