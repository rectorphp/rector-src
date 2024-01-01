<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer;

use PhpParser\Node;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\NodeTypeResolver\PhpDocNodeVisitor\ClassRenamePhpDocNodeVisitor;
use Rector\NodeTypeResolver\ValueObject\OldToNewType;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;

final readonly class DocBlockClassRenamer
{
    public function __construct(
        private ClassRenamePhpDocNodeVisitor $classRenamePhpDocNodeVisitor,
    ) {
    }

    /**
     * @param OldToNewType[] $oldToNewTypes
     */
    public function renamePhpDocType(PhpDocInfo $phpDocInfo, array $oldToNewTypes, Node $currentPhpNode): bool
    {
        if ($oldToNewTypes === []) {
            return false;
        }

        $phpDocNodeTraverser = new PhpDocNodeTraverser();
        $phpDocNodeTraverser->addPhpDocNodeVisitor($this->classRenamePhpDocNodeVisitor);

        $this->classRenamePhpDocNodeVisitor->setCurrentPhpNode($currentPhpNode);

        $this->classRenamePhpDocNodeVisitor->setOldToNewTypes($oldToNewTypes);

        $phpDocNodeTraverser->traverse($phpDocInfo->getPhpDocNode());

        return $this->classRenamePhpDocNodeVisitor->hasChanged();
    }
}
