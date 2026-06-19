<?php

declare(strict_types=1);

namespace Rector\Privatization\Guard;

use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ReflectionProvider;

/**
 * Verify whether Class_'s method or property allowed to be overridden by verify class parent or implements exists
 */
final readonly class OverrideByParentClassGuard
{
    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function isLegal(Class_ $class): bool
    {
        if ($class->extends instanceof FullyQualified && ! $this->reflectionProvider->hasClass(
            $class->extends->toString()
        )) {
            return false;
        }

        return array_all(
            $class->implements,
            fn (Name $name): bool => $this->reflectionProvider->hasClass($name->toString())
        );
    }
}
