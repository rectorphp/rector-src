<?php

declare(strict_types=1);

namespace Rector\Privatization\Guard;

use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use Rector\Reflection\ClassReflectionProvider;

/**
 * Verify whether Class_'s method or property allowed to be overridden by verify class parent or implements exists
 */
final readonly class OverrideByParentClassGuard
{
    public function __construct(
        private ClassReflectionProvider $classReflectionProvider
    ) {
    }

    public function isLegal(Class_ $class): bool
    {
        if ($class->extends instanceof FullyQualified && ! $this->classReflectionProvider->hasClass(
            $class->extends->toString()
        )) {
            return false;
        }

        return array_all(
            $class->implements,
            fn (Name $name): bool => $this->classReflectionProvider->hasClass($name->toString())
        );
    }
}
