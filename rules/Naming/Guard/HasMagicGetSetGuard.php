<?php

declare(strict_types=1);

namespace Rector\Naming\Guard;

use PHPStan\Reflection\ReflectionProvider;
use Rector\Naming\ValueObject\PropertyRename;

final readonly class HasMagicGetSetGuard
{
    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function isConflicting(PropertyRename $propertyRename): bool
    {
        if (! $this->reflectionProvider->hasClass($propertyRename->getClassLikeName())) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($propertyRename->getClassLikeName());
        if ($classReflection->hasMethod('__set')) {
            return true;
        }

        return $classReflection->hasMethod('__get');
    }
}
