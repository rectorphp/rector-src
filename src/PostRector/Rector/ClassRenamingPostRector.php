<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Node;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeVisitor;
use Rector\CodingStyle\Application\UseImportsRemover;
use Rector\Configuration\RenamedClassesDataCollector;
use Rector\PhpParser\Node\FileNode;
use Rector\PostRector\Guard\AddUseStatementGuard;
use Rector\Renaming\Collector\RenamedNameCollector;

final class ClassRenamingPostRector extends AbstractPostRector
{
    /**
     * @var array<string, string>
     */
    private array $oldToNewClasses = [];

    public function __construct(
        private readonly RenamedClassesDataCollector $renamedClassesDataCollector,
        private readonly UseImportsRemover $useImportsRemover,
        private readonly RenamedNameCollector $renamedNameCollector,
        private readonly AddUseStatementGuard $addUseStatementGuard,
    ) {
    }

    public function enterNode(Node $node): Namespace_|FileNode|int|null
    {
        if ($node instanceof FileNode) {
            // handle in Namespace_ node
            if ($node->isNamespaced()) {
                return null;
            }

            // handle here
            $removedUses = $this->renamedClassesDataCollector->getOldClasses();
            if ($this->useImportsRemover->removeImportsFromStmts($node, $removedUses)) {
                $this->addRectorClassWithLine($node);
            }

            $this->renamedNameCollector->reset();

            return $node;
        }

        if ($node instanceof Namespace_) {
            $removedUses = $this->renamedClassesDataCollector->getOldClasses();
            if ($this->useImportsRemover->removeImportsFromStmts($node, $removedUses)) {
                $this->addRectorClassWithLine($node);
            }

            $this->renamedNameCollector->reset();

            return $node;
        }

        // nothing else to handle here, as first 2 nodes we'll hit are handled above
        return NodeVisitor::STOP_TRAVERSAL;
    }

    public function shouldTraverse(array $stmts): bool
    {
        $this->oldToNewClasses = $this->renamedClassesDataCollector->getOldToNewClasses();

        if ($this->oldToNewClasses === []) {
            return false;
        }

        return $this->addUseStatementGuard->shouldTraverse($stmts, $this->getFile()->getFilePath());
    }
}
