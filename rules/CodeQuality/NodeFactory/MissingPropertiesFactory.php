<?php

declare(strict_types=1);

namespace Rector\CodeQuality\NodeFactory;

use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PHPStan\Type\Type;

final class MissingPropertiesFactory
{
    public function __construct(
        private readonly PropertyTypeDecorator $propertyTypeDecorator
    ) {
    }

    /**
     * @param array<string, Type> $fetchedLocalPropertyNameToTypes
     * @param string[] $propertyNamesToComplete
     * @return Property[]
     */
    public function create(array $fetchedLocalPropertyNameToTypes, array $propertyNamesToComplete): array
    {
        $newProperties = [];
        foreach ($fetchedLocalPropertyNameToTypes as $propertyName => $propertyType) {
            if (! in_array($propertyName, $propertyNamesToComplete, true)) {
                continue;
            }

            $property = new Property(\PhpParser\Node\Stmt\Class_::MODIFIER_PUBLIC, [
                new PropertyProperty($propertyName),
            ]);
            $this->propertyTypeDecorator->decorateProperty($property, $propertyType);

            $newProperties[] = $property;
        }

        return $newProperties;
    }
}
