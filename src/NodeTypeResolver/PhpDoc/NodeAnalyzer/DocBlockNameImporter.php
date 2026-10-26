<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer;

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\NodeTraverser;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use Rector\NodeTypeResolver\PhpDocNodeVisitor\NameImportingPhpDocNodeVisitor;

final readonly class DocBlockNameImporter
{
    public function __construct(
        private NameImportingPhpDocNodeVisitor $nameImportingPhpDocNodeVisitor,
    ) {
    }

    public function importNames(PhpDocNode $phpDocNode, Node $node): bool
    {
        if ($phpDocNode->children === []) {
            return false;
        }

        $this->nameImportingPhpDocNodeVisitor->setCurrentNode($node);

        $phpDocNodeTraverser = new NodeTraverser([$this->nameImportingPhpDocNodeVisitor]);
        $phpDocNodeTraverser->traverse([$phpDocNode]);

        return $this->nameImportingPhpDocNodeVisitor->hasChanged();
    }
}
