<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\InlineHTML;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer\DocBlockNameImporter;
use Rector\PhpParser\Node\BetterNodeFinder;

final class DocblockNameImportingPostRector extends AbstractPostRector
{
    public function __construct(
        private readonly DocBlockNameImporter $docBlockNameImporter,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly BetterNodeFinder $betterNodeFinder,
    ) {
    }

    public function enterNode(Node $node): Node|int|null
    {
        if (! $node instanceof Stmt && ! $node instanceof Param) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return null;
        }

        $hasDocChanged = $this->docBlockNameImporter->importNames($phpDocInfo->getPhpDocNode(), $node);
        if (! $hasDocChanged) {
            return null;
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);
        return $node;
    }

    /**
     * @param Stmt[] $stmts
     */
    public function shouldTraverse(array $stmts): bool
    {
        return ! $this->betterNodeFinder->hasInstancesOf($stmts, [InlineHTML::class]);
    }
}
