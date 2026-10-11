<?php

declare(strict_types=1);

namespace Rector\Application;

use PhpParser\Node;
use Rector\Configuration\ConfigurationRuleFilter;
use Rector\Contract\Rector\RectorInterface;
use Rector\VersionBonding\ComposerPackageConstraintFilter;
use Rector\VersionBonding\PhpVersionedFilter;

/**
 * @see \Rector\Tests\Application\RectorRegistryTest
 */
final class RectorRegistry
{
    private bool $areRectorsPrepared = false;

    /**
     * @var array<class-string<Node>, RectorInterface[]>
     */
    private array $cache = [];

    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private readonly PhpVersionedFilter $phpVersionedFilter,
        private readonly ComposerPackageConstraintFilter $composerPackageConstraintFilter,
        private readonly ConfigurationRuleFilter $configurationRuleFilter,
        private array $rectors,
    ) {
    }

    /**
     * @param RectorInterface[] $rectors
     * @api used in tests to update the active rules
     *
     * @internal Used only in Rector core, not supported outside. Might change any time.
     */
    public function refreshRectors(array $rectors): void
    {
        $this->rectors = $rectors;
        $this->areRectorsPrepared = false;
        $this->cache = [];
    }

    /**
     * @return RectorInterface[]
     *
     * @api used in tests
     */
    public function forNode(Node $node): array
    {
        $this->prepareRectors();

        $nodeClass = $node::class;

        if (! isset($this->cache[$nodeClass])) {
            $this->cache[$nodeClass] = [];

            foreach ($this->rectors as $rector) {
                foreach ($rector->getNodeTypes() as $nodeType) {
                    if (is_a($nodeClass, $nodeType, true)) {
                        $this->cache[$nodeClass][] = $rector;
                        continue 2;
                    }
                }
            }
        }

        return $this->cache[$nodeClass];
    }

    /**
     * Filtering must happen lazily, after the configuration is set in ProcessCommand::execute(),
     * otherwise options like --only are not yet available and get ignored.
     */
    private function prepareRectors(): void
    {
        if ($this->areRectorsPrepared) {
            return;
        }

        $this->rectors = $this->phpVersionedFilter->filter($this->rectors);
        $this->rectors = $this->composerPackageConstraintFilter->filter($this->rectors);
        $this->rectors = $this->configurationRuleFilter->filter($this->rectors);

        $this->areRectorsPrepared = true;
    }
}
