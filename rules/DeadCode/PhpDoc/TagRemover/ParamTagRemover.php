<?php

declare(strict_types=1);

namespace Rector\DeadCode\PhpDoc\TagRemover;

use PHPStan\Type\Type;
use PhpParser\Node\FunctionLike;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\DeadCode\PhpDoc\DeadParamTagValueNodeAnalyzer;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;

final readonly class ParamTagRemover
{
    public function __construct(
        private DeadParamTagValueNodeAnalyzer $deadParamTagValueNodeAnalyzer,
        private DocBlockUpdater $docBlockUpdater,
    ) {
    }

    public function removeParamTagsIfUseless(PhpDocInfo $phpDocInfo, FunctionLike $functionLike, ?Type $type = null): bool
    {
        $hasChanged = false;

        $phpDocNodeTraverser = new PhpDocNodeTraverser();
        $phpDocNodeTraverser->traverseWithCallable($phpDocInfo->getPhpDocNode(), '', function (Node $docNode) use (
            $functionLike,
            &$hasChanged,
            $type,
            $phpDocInfo
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

            $paramType = $phpDocInfo->getParamType($docNode->value->parameterName);
            if ($type instanceof Type) {
                if ($type->equals($paramType)) {
                    $hasChanged = true;
                    return PhpDocNodeTraverser::NODE_REMOVE;
                }

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
