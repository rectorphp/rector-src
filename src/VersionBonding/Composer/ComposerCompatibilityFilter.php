<?php

namespace Rector\VersionBonding\Composer;

use Rector\Contract\Rector\RectorInterface;
use Rector\VersionBonding\Contract\RequiredComposerCompatibilityInterface;

final readonly class ComposerCompatibilityFilter
{
    public function __construct(
        protected ComposerContextSwitcher $contextSwitcher
    ) {
    }

    /**
     * @param array<RectorInterface> $rectors
     * @return array<RectorInterface>
     */
    public function filter(iterable $rectors): array
    {
        $this->contextSwitcher->loadTargetDependencies();
        $this->contextSwitcher->setComposerToTargetDependencies();
        $analyzer = new StandardComposerAnalyzer();

        $activeRectors = [];
        foreach ($rectors as $rector) {
            if (
                ($rector instanceof RequiredComposerCompatibilityInterface &&
                $rector->meetsComposerRequirements($analyzer)) ||
                ! $rector instanceof RequiredComposerCompatibilityInterface
            ) {
                $activeRectors[] = $rector;
            }
        }

        $this->contextSwitcher->reset();

        return $activeRectors;
    }
}
