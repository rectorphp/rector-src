<?php

declare(strict_types=1);

namespace Rector\CodeQuality\NodeFactory;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Privatization\TypeManipulator\TypeNormalizer;

final class MissingPropertiesFactory
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly TypeNormalizer $typeNormalizer,
        private readonly PhpDocTypeChanger $phpDocTypeChanger
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

            $property = new Property(Class_::MODIFIER_PUBLIC, [new PropertyProperty($propertyName)]);

            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($property);
            $phpDocInfo->makeMultiLined();

            // generalize false/true type to bool, as mostly default value but accepts both
            $propertyType = $this->typeNormalizer->generalizeConstantBoolTypes($propertyType);

            $this->phpDocTypeChanger->changeVarType($phpDocInfo, $propertyType);

            $newProperties[] = $property;
        }

        return $newProperties;
    }
}
