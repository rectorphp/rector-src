<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks;

use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Privatization\TypeManipulator\TypeNormalizer;
use Rector\StaticTypeMapper\StaticTypeMapper;

final readonly class NodeDocblockTypeDecorator
{
    public  function __construct(
        private TypeNormalizer $typeNormalizer,
        private StaticTypeMapper $staticTypeMapper,
        private DocBlockUpdater $docBlockUpdater,
    ) {
    }

    public function decorateGenericIterableVarType(
        Type $type,
        PhpDocInfo $phpDocInfo,
        Property $property
    ): void {
        $typeNode = $this->createTypeNode($type);

        $varTagValueNode = new VarTagValueNode($typeNode, '', '');

        $this->addTagValueNodeAndUpdatePhpDocInfo($phpDocInfo, $varTagValueNode, $property);
    }

    private function createTypeNode(Type $type): TypeNode
    {
        $generalizedReturnType = $this->typeNormalizer->generalizeConstantTypes($type);

        // turn into rather generic short return type, to keep it open to extension later and readable to human
        return $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($generalizedReturnType);
    }

    private function addTagValueNodeAndUpdatePhpDocInfo(PhpDocInfo $phpDocInfo, VarTagValueNode $varTagValueNode, Property $property): void
    {
        $phpDocInfo->addTagValueNode($varTagValueNode);

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($property);
    }
}
