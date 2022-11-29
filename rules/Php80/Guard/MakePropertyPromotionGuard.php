<?php

declare(strict_types=1);

namespace Rector\Php80\Guard;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\Php74\Guard\PropertyTypeChangeGuard;

final class MakePropertyPromotionGuard
{
    public function __construct(
        private readonly PropertyTypeChangeGuard $propertyTypeChangeGuard
    ) {
    }

    public function isLegal(Class_ $class, Property $property, Param $param, bool $inlinePublic = true): bool
    {
        if (! $this->propertyTypeChangeGuard->isLegal($property, $inlinePublic)) {
            return false;
        }

        if ($class->isFinal()) {
            return true;
        }

        if (! $inlinePublic && ! $property->isPrivate() && $param->type instanceof Node && ! $property->type instanceof Node) {
            return false;
        }

        return true;
    }
}
