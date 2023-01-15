<?php

declare(strict_types=1);

namespace Rector\CodeQuality\NodeFactory;

use PhpParser\Node\Stmt\Property;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Privatization\TypeManipulator\TypeNormalizer;

final class PropertyTypeDecorator
{
    public function __construct(
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly TypeNormalizer $typeNormalizer
    ) {
    }

    public function decorateProperty(Property $property, Type $propertyType): void
    {
        // generalize false/true type to bool, as mostly default value but accepts both
        $propertyType = $this->typeNormalizer->generalizeConstantBoolTypes($propertyType);

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($property);
        $phpDocInfo->makeMultiLined();

        $this->phpDocTypeChanger->changeVarType($phpDocInfo, $propertyType);
    }
}
