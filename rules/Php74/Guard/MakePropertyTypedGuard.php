<?php

declare(strict_types=1);

namespace Rector\Php74\Guard;

use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ClassReflection;

final class MakePropertyTypedGuard
{
    public function __construct(
        private readonly PropertyTypeChangeGuard $propertyTypeChangeGuard
    ) {
    }

    public function isLegal(Property $property, ClassReflection $classReflection, bool $inlinePublic = true): bool
    {
        if ($property->type !== null) {
            return false;
        }

        return $this->propertyTypeChangeGuard->isLegal($property, $classReflection, $inlinePublic);
    }
}
