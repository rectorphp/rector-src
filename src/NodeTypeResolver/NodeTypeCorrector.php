<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver;

use PHPStan\Type\Accessory\AccessoryArrayListType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\NodeTypeCorrector\AccessoryNonEmptyArrayTypeCorrector;
use Rector\NodeTypeResolver\NodeTypeCorrector\AccessoryNonEmptyStringTypeCorrector;
use Rector\NodeTypeResolver\NodeTypeCorrector\GenericClassStringTypeCorrector;

/**
 * This service correct unnecessary intersection/union types that do not bring any value.
 * We focus on scalar types like "array", "string", "int" etc.,
 * to print them as valid type declarations.
 */
final readonly class NodeTypeCorrector
{
    public function __construct(
        private AccessoryNonEmptyStringTypeCorrector $accessoryNonEmptyStringTypeCorrector,
        private GenericClassStringTypeCorrector $genericClassStringTypeCorrector,
        private AccessoryNonEmptyArrayTypeCorrector $accessoryNonEmptyArrayTypeCorrector,
    ) {
    }

    public function correctType(Type $type): Type
    {
        $type = $this->accessoryNonEmptyStringTypeCorrector->correct($type);
        $type = $this->genericClassStringTypeCorrector->correct($type);

        $type = $this->removeAccessoryArrayListType($type);

        return $this->accessoryNonEmptyArrayTypeCorrector->correct($type);
    }

    private function removeAccessoryArrayListType(Type $type): Type
    {
        if (! $type instanceof IntersectionType) {
            return $type;
        }

        $cleanTypes = [];
        foreach ($type->getTypes() as $intersectionType) {
            if ($intersectionType instanceof AccessoryArrayListType) {
                continue;
            }

            $cleanTypes[] = $intersectionType;
        }

        if (count($cleanTypes) === 1) {
            return $cleanTypes[0];
        }

        return new IntersectionType($cleanTypes);
    }
}
