<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer;

use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\BetterPhpDocParser\Annotation\AnnotationNaming;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;

final class DocBlockTagReplacer
{
    public function __construct(
        private readonly AnnotationNaming $annotationNaming,
        private readonly DocBlockUpdater $docBlockUpdater
    ) {
    }

    public function replaceTagByAnother(PhpDocInfo $phpDocInfo, string $oldTag, string $newTag): bool
    {
        $hasChanged = false;

        $oldTag = $this->annotationNaming->normalizeName($oldTag);
        $newTag = $this->annotationNaming->normalizeName($newTag);

        $phpDocNode = $phpDocInfo->getPhpDocNode();
        foreach ($phpDocNode->children as $key => $phpDocChildNode) {
            if (! $phpDocChildNode instanceof PhpDocTagNode) {
                continue;
            }

            if ($phpDocChildNode->name !== $oldTag) {
                continue;
            }

            unset($phpDocNode->children[$key]);
            $phpDocNode->children[] = new PhpDocTagNode($newTag, new GenericTagValueNode(''));
            $hasChanged = true;
        }

        if ($hasChanged) {
            $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($phpDocInfo->getNode());
        }

        return $hasChanged;
    }
}
