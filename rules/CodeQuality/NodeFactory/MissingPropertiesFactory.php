<?php

declare(strict_types=1);

namespace Rector\CodeQuality\NodeFactory;

use PhpParser\Modifiers;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\PropertyItem;
use PHPStan\Type\Type;

final readonly class MissingPropertiesFactory
{
    public function __construct(
        private PropertyTypeDecorator $propertyTypeDecorator
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

            $property = new Property(Modifiers::PUBLIC, [new PropertyItem($propertyName)]);
            $this->propertyTypeDecorator->decorateProperty($property, $propertyType);

            $newProperties[] = $property;
        }

        return $newProperties;
    }
}
