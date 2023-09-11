<?php

declare(strict_types=1);

namespace Rector\DeadCode\PhpDoc\TagRemover;

use PhpParser\Node\FunctionLike;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\DeadCode\PhpDoc\DeadParamTagValueNodeAnalyzer;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;

final class ParamTagRemover
{
    public function __construct(
        private readonly DeadParamTagValueNodeAnalyzer $deadParamTagValueNodeAnalyzer,
        private readonly DocBlockUpdater $docBlockUpdater,
    ) {
    }

    public function removeParamTagsIfUseless(PhpDocInfo $phpDocInfo, FunctionLike $functionLike): bool
    {
        $hasChanged = false;

        $phpDocNodeTraverser = new PhpDocNodeTraverser();
        $phpDocNodeTraverser->traverseWithCallable($phpDocInfo->getPhpDocNode(), '', function (Node $docNode) use (
            $functionLike,
            &$hasChanged
        ): ?int {
            if (! $docNode instanceof PhpDocTagNode) {
                return null;
            }

            if (! $docNode->value instanceof ParamTagValueNode) {
                return null;
            }

            // handle only basic types, keep phpstan/psalm helper ones
            if ($docNode->name !== '@param') {
                return null;
            }

            // skip union types
            if ($docNode->value->type instanceof UnionTypeNode) {
                return null;
            }

            if (! $this->deadParamTagValueNodeAnalyzer->isDead($docNode->value, $functionLike)) {
                return null;
            }

            $hasChanged = true;
            return PhpDocNodeTraverser::NODE_REMOVE;
        });

        if ($hasChanged) {
            $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($functionLike);
        }

        return $hasChanged;
    }
}
