<?php

declare(strict_types=1);

namespace Rector\CodeQuality\NodeFactory;

use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Property;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\Php\PhpVersionProvider;
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
