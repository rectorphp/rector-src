<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\TagNodeAnalyzer;

use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;

final class UsefulArrayTagNodeAnalyzer
{
    public function isUsefulArrayTag(null|ReturnTagValueNode|ParamTagValueNode|VarTagValueNode $tagValueNode): bool
    {
        if (! $tagValueNode instanceof ReturnTagValueNode && ! $tagValueNode instanceof ParamTagValueNode && ! $tagValueNode instanceof VarTagValueNode) {
            return false;
        }

        $type = $tagValueNode->type;
        if (! $type instanceof IdentifierTypeNode) {
            return true;
        }

        return ! in_array($type->name, ['array', 'mixed'], true);
    }
}
