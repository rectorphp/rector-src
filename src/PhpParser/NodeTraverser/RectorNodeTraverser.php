<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\NodeTraverser;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\VersionBonding\PhpVersionedFilter;
use Webmozart\Assert\Assert;

final class RectorNodeTraverser extends NodeTraverser
{
    private bool $areNodeVisitorsPrepared = false;

    /**
     * @param PhpRectorInterface[] $phpRectors
     */
    public function __construct(
        private array $phpRectors,
        private NodeFinder $nodeFinder,
        private PhpVersionedFilter $phpVersionedFilter
    ) {
    }

    /**
     * @template TNode as Node
     * @param TNode[] $nodes
     * @return TNode[]|FileWithoutNamespace[]
     */
    public function traverse(array $nodes): array
    {
        $this->prepareNodeVisitors();

        $hasNamespace = (bool) $this->nodeFinder->findFirstInstanceOf($nodes, Namespace_::class);
        if (! $hasNamespace && $nodes !== []) {
            Assert::allIsInstanceOf($nodes, Stmt::class);

            $fileWithoutNamespace = new FileWithoutNamespace($nodes);
            return parent::traverse([$fileWithoutNamespace]);
        }

        return parent::traverse($nodes);
    }

    /**
     * This must happen after $this->configuration is set after ProcessCommand::execute() is run,
     * otherwise we get default false positives.
     *
     * This hack should be removed after https://github.com/rectorphp/rector/issues/5584 is resolved
     */
    private function prepareNodeVisitors(): void
    {
        if ($this->areNodeVisitorsPrepared) {
            return;
        }

        // filer out by version
        $activePhpRectors = $this->phpVersionedFilter->filter($this->phpRectors);
        foreach ($activePhpRectors as $activePhpRector) {
            $this->addVisitor($activePhpRector);
        }

        $this->areNodeVisitorsPrepared = true;
    }
}
