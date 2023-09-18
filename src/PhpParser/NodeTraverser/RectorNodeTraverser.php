<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\NodeTraverser;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use Rector\Core\Contract\Rector\CollectorRectorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractCollectorRector;
use Rector\Core\ValueObject\Configuration;
use Rector\VersionBonding\PhpVersionedFilter;

final class RectorNodeTraverser extends NodeTraverser
{
    private bool $areNodeVisitorsPrepared = false;

    /**
     * @param RectorInterface[] $rectors
     * @param CollectorRectorInterface[] $collectorRectors
     */
    public function __construct(
        private array $rectors,
        private array $collectorRectors,
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

    public function prepareCollectorRectorsRun(Configuration $configuration): void
    {
        if ($this->collectorRectors === []) {
            throw new ShouldNotHappenException(
                'There are no ConfigurableRectorInterface rules to run. Register them first.'
            );
        }

        // hydrate abstract collector rector with configuration
        foreach ($this->collectorRectors as $collectorRector) {
            /** @var AbstractCollectorRector $collectorRector */
            $collectorRector->setCollectedDatas($configuration->getCollectedData());
        }

        $this->visitors = $this->collectorRectors;
        $this->areNodeVisitorsPrepared = true;
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
        $activePhpRectors = $this->phpVersionedFilter->filter($this->rectors);

        $this->visitors = array_merge($this->visitors, $activePhpRectors);

        $this->areNodeVisitorsPrepared = true;
    }
}
