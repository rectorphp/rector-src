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
<<<<<<< HEAD

        return array_all(
            $class->implements,
            fn (Name $name): bool => $this->reflectionProvider->hasClass($name->toString())
=======
        return array_all(
            $class->implements,
            fn ($implement): bool => $this->reflectionProvider->hasClass($implement->toString())
>>>>>>> 424f600506 ([php] bump to PHP 8.4 syntax)
        );
    }
}
