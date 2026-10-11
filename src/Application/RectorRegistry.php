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
    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private PhpVersionedFilter $phpVersionedFilter,
        private ComposerPackageConstraintFilter $composerPackageConstraintFilter,
        private ConfigurationRuleFilter $configurationRuleFilter,
        private array $rectors,
    ) {
        $this->rectors = $this->filterRectors($rectors);
    }

    /**
     * @param RectorInterface[] $rectors
     * @api used in tests to update the active rules
     *
     * @internal Used only in Rector core, not supported outside. Might change any time.
     */
    public function refreshRectors(array $rectors): void
    {
        $this->rectors = $this->filterRectors($rectors);
    }

    /**
     * @var array<class-string<Node>, RectorInterface[]>
     */
    private array $cache = [];

    /**
     * @return RectorInterface[]
     *
     * @api used in tests
     */
    public function forNode(Node $node): array
    {
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
     * @param RectorInterface[] $rectors
     * @return RectorInterface[]
     *
     * @api used in tests to update the active rules
     */
    private function filterRectors(array $rectors): array
    {
        $rectors = $this->phpVersionedFilter->filter($rectors);
        $rectors = $this->composerPackageConstraintFilter->filter($rectors);

        return $this->configurationRuleFilter->filter($rectors);
    }
}
