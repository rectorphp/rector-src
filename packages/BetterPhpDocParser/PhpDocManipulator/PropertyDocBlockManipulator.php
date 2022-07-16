<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\PhpDocManipulator;

use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;
use Rector\Naming\ValueObject\ParamRename;

final class PropertyDocBlockManipulator
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory
    ) {
    }

    public function renameParameterNameInDocBlock(ParamRename $paramRename): void
    {
        $functionLike = $paramRename->getFunctionLike();

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($functionLike);
        $paramTagValueNode = $phpDocInfo->getParamTagValueNodeByName($paramRename->getCurrentName());
        if (! $paramTagValueNode instanceof ParamTagValueNode) {
            return;
        }

        $paramTagValueNode->parameterName = '$' . $paramRename->getExpectedName();
        $paramTagValueNode->setAttribute(PhpDocAttributeKey::ORIG_NODE, null);
    }
}
