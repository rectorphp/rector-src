<?php

declare(strict_types=1);

namespace Rector\Naming\Guard;

use Rector\Naming\ValueObject\PropertyRename;
use Rector\Reflection\ClassReflectionProvider;

final readonly class HasMagicGetSetGuard
{
    public function __construct(
        private ClassReflectionProvider $classReflectionProvider
    ) {
    }

    public function isConflicting(PropertyRename $propertyRename): bool
    {
        if (! $this->classReflectionProvider->hasClass($propertyRename->getClassLikeName())) {
            return false;
        }

        $classReflection = $this->classReflectionProvider->getClass($propertyRename->getClassLikeName());
        if ($classReflection->hasMethod('__set')) {
            return true;
        }

        return $classReflection->hasMethod('__get');
    }
}
