<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\PhpDocParser;

use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\Type\ArrayType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\StaticTypeMapper\StaticTypeMapper;

final readonly class ReturnPhpDocDecorator
{
    public function __construct(
        private PhpDocInfoFactory $phpDocInfoFactory,
        private StaticTypeMapper $staticTypeMapper,
        private DocBlockUpdater $docBlockUpdater,
    ) {
    }

    public function decorateWithArray(ArrayType $arrayType, ClassMethod|Function_ $functionLike): bool
    {
        //    private function updateFunctionLikeReturnDocBlock(
        //        array $closureReturnTypes,
        //        ClassMethod|Function_ $functionLike
        //    ): null|Function_|ClassMethod {
        //        if (count($closureReturnTypes) !== 1) {
        //            return null;
        //        }

        // easy return
        $functionLikePhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($functionLike);

        // has already filled @return?
        if ($functionLikePhpDocInfo->getReturnTagValue() instanceof ReturnTagValueNode) {
            // @todo extend for mixed/dummy array
            return false;
        }

        //        $closureReturnType = $closureReturnTypes[0];

        $phpDocReturnTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($arrayType);
        $functionLikePhpDocInfo->addTagValueNode(new ReturnTagValueNode($phpDocReturnTypeNode, ''));

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($functionLike);

        return true;
    }
}
