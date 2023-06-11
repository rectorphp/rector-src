<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\NodeTraverser;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Rector\VersionBonding\PhpVersionedFilter;

final class RectorNodeTraverser extends NodeTraverser
{
    private bool $areNodeVisitorsPrepared = false;

    private array $visitorsToVisit = [];

    /**
     * @param PhpRectorInterface[] $phpRectors
     */
    public function __construct(
        private readonly array $phpRectors,
        private readonly PhpVersionedFilter $phpVersionedFilter
    ) {
        parent::__construct();
    }

    /**
     * @template TNode as Node
     * @param TNode[] $nodes
     * @return TNode[]
     */
    public function traverse(array $nodes): array
    {
        $this->prepareNodeVisitors();
        foreach ($this->visitorsToVisit as $visitor) {
            $this->visitors = [$visitor];

            $nodes = parent::traverse($nodes);
            $this->stopTraversal = false;
        }

        return $nodes;
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
        $this->visitorsToVisit = array_merge($this->visitors, $activePhpRectors);

        $this->areNodeVisitorsPrepared = true;
    }
}
