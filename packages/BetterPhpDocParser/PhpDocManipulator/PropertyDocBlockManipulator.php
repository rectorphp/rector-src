<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\PhpDocManipulator;

use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;
use Rector\Naming\Contract\RenameValueObjectInterface;
use Rector\Naming\ValueObject\ParamRename;

final class PropertyDocBlockManipulator
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory
    ) {
    }

    /**
     * @param ParamRename $renameValueObject
     */
    public function renameParameterNameInDocBlock(RenameValueObjectInterface $renameValueObject): void
    {
        $functionLike = $renameValueObject->getFunctionLike();

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($functionLike);
        $paramTagValueNode = $phpDocInfo->getParamTagValueNodeByName($renameValueObject->getCurrentName());
        if (! $paramTagValueNode instanceof ParamTagValueNode) {
            return;
        }

        $paramTagValueNode->parameterName = '$' . $renameValueObject->getExpectedName();
        $paramTagValueNode->setAttribute(PhpDocAttributeKey::ORIG_NODE, null);
    }
}
