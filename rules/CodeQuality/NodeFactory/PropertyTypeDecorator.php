<?php

declare(strict_types=1);

namespace Rector\CodeQuality\NodeFactory;

use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Privatization\TypeManipulator\TypeNormalizer;
use Rector\StaticTypeMapper\StaticTypeMapper;

/**
 * @api downgrade
 */
final class PropertyTypeDecorator
{
    public function __construct(
        private readonly PhpVersionProvider $phpVersionProvider,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly TypeNormalizer $typeNormalizer,
    ) {
    }

    public function decoratePropertyWithDocBlock(Property $property, ComplexType|Identifier|Name $typeNode): void
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($property);
        if ($phpDocInfo->getVarTagValueNode() !== null) {
            return;
        }

        $newType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($typeNode);
        $this->phpDocTypeChanger->changeVarType($phpDocInfo, $newType);
    }
}
