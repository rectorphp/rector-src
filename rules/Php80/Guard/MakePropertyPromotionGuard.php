<?php

declare(strict_types=1);

namespace Rector\Php80\Guard;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Property;
use Rector\Php74\Guard\PropertyTypeChangeGuard;

final class MakePropertyPromotionGuard
{
    public function __construct(
        private readonly PropertyTypeChangeGuard $propertyTypeChangeGuard
    ) {
    }

    public function isLegal(Property $property, Param $param, bool $inlinePublic = true): bool
    {
        if ($this->propertyTypeChangeGuard->isLegal($property, $inlinePublic)) {
            return true;
        }

        if ($property->type instanceof Node) {
            return true;
        }

        return ! $param->type instanceof Node;
    }
}
