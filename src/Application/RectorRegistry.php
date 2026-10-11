<?php

declare(strict_types=1);

namespace Rector\Application;

<<<<<<< HEAD
<<<<<<< HEAD
use Rector\Contract\Rector\RectorInterface;
=======
use PhpParser\Node;
=======
>>>>>>> 1babe5c467 (fixup! move rule selection to RectorRegistry)
use Rector\Configuration\ConfigurationRuleFilter;
use Rector\Contract\Rector\HTMLAverseRectorInterface;
use Rector\Contract\Rector\RectorInterface;
use Rector\ValueObject\Application\File;
use Rector\VersionBonding\ComposerPackageConstraintFilter;
use Rector\VersionBonding\PhpVersionedFilter;
<<<<<<< HEAD
use Rector\VersionBonding\ValueObject\ComposerPackageConstraint;
>>>>>>> 7fbb048d92 (move rule selection to RectorRegistry)
=======
>>>>>>> 1babe5c467 (fixup! move rule selection to RectorRegistry)

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
    }

    /**
     * @param array<RectorInterface> $rectors
     * @return array<RectorInterface>
     */
    private function filteredRectors(array $rectors): array
    {
        $rectors = $this->phpVersionedFilter->filter($rectors);
        $rectors = $this->composerPackageConstraintFilter->filter($rectors);

        return $this->configurationRuleFilter->filter($rectors);
    }

    /**
     * @param RectorInterface[] $rectors
     * @api used in tests to update the active rules
     *
     * @internal Used only in Rector core, not supported outside. Might change any time.
     */
    public function refreshRectors(array $rectors): void
    {
        $this->rectors = $this->filteredRectors($rectors);
    }

    /**
     * @return array<RectorInterface>
     */
    public function getRectors(): array
    {
<<<<<<< HEAD
        return $this->rectors;
=======
        $rectorsForFile = $this->filteredRectors($this->rectors);

        if ($file->containsHTML()) {
            $rectorsForFile = array_filter($rectorsForFile, static function (RectorInterface $rector): bool {
                return $rector instanceof HTMLAverseRectorInterface;
            });
        }

        return $rectorsForFile;
>>>>>>> 7fbb048d92 (move rule selection to RectorRegistry)
    }
}
