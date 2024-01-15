<?php

declare(strict_types=1);

namespace Rector\PhpParser\NodeTraverser;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use Rector\Contract\Rector\CollectorRectorInterface;
use Rector\Contract\Rector\RectorInterface;
use Rector\ValueObject\Configuration;
use Rector\VersionBonding\PhpVersionedFilter;

final class RectorNodeTraverser extends NodeTraverser
{
    private bool $areNodeVisitorsPrepared = false;

    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private array $rectors,
        private readonly PhpVersionedFilter $phpVersionedFilter
    ) {
        parent::__construct();
    }

    /**
     * @param Node[] $nodes
     * @return Node[]
     */
    public function traverse(array $nodes): array
    {
        $this->prepareNodeVisitors();

        return parent::traverse($nodes);
    }

    /**
     * @param RectorInterface[] $rectors
     * @api used in tests to update the active rules
     */
    public function refreshPhpRectors(array $rectors): void
    {
        $this->rectors = $rectors;
        $this->visitors = [];

        $this->areNodeVisitorsPrepared = false;
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
        $activeRectors = $this->phpVersionedFilter->filter($this->rectors);

        $nonCollectorActiveRectors = array_filter(
            $activeRectors,
            static fn (RectorInterface $rector): bool => ! $rector instanceof CollectorRectorInterface
        );

        $this->visitors = [...$this->visitors, ...$nonCollectorActiveRectors];

        $this->areNodeVisitorsPrepared = true;
    }
}
