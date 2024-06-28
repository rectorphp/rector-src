<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer;

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use Rector\NodeTypeResolver\PhpDocNodeVisitor\NameImportingPhpDocNodeVisitor;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;
use Rector\ValueObject\Application\File;

final readonly class DocBlockNameImporter
{
    public function __construct(
        private NameImportingPhpDocNodeVisitor $nameImportingPhpDocNodeVisitor,
    ) {
    }

    public function importNames(PhpDocNode $phpDocNode, Node $node, File $file): bool
    {
        if ($phpDocNode->children === []) {
            return false;
        }

        $this->nameImportingPhpDocNodeVisitor->setCurrentFileAndNode($file, $node);

        $phpDocNodeTraverser = new PhpDocNodeTraverser();
        $phpDocNodeTraverser->addPhpDocNodeVisitor($this->nameImportingPhpDocNodeVisitor);
        $phpDocNodeTraverser->traverse($phpDocNode);

        return $this->nameImportingPhpDocNodeVisitor->hasChanged();
    }
}
