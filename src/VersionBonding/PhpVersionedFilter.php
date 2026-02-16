<?php

declare(strict_types=1);

namespace Rector\VersionBonding;

use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Contract\Rector\RectorInterface;
use Rector\Php\PhpVersionProvider;
use Rector\Php\PolyfillPackagesProvider;
use Rector\VersionBonding\Contract\DeprecatedAtVersionInterface;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Rector\VersionBonding\Contract\RelatedPolyfillInterface;

/**
 * @see \Rector\Tests\VersionBonding\PhpVersionedFilterTest
 */
final readonly class PhpVersionedFilter
{
    public function __construct(
        private PhpVersionProvider $phpVersionProvider,
        private PolyfillPackagesProvider $polyfillPackagesProvider,
    ) {
    }

    /**
     * @param list<RectorInterface> $rectors
     * @return list<RectorInterface>
     */
    public function filter(array $rectors): array
    {
        $minProjectPhpVersion = $this->phpVersionProvider->provide();
        $isEagerlyResolveDeprecations = SimpleParameterProvider::provideBoolParameter(
            Option::EAGERLY_RESOLVE_DEPRECATIONS,
            false
        );

        $activeRectors = [];
        foreach ($rectors as $rector) {
            if ($rector instanceof RelatedPolyfillInterface) {
                $polyfillPackageNames = $this->polyfillPackagesProvider->provide();

                if (in_array($rector->providePolyfillPackage(), $polyfillPackageNames, true)) {
                    $activeRectors[] = $rector;
                    continue;
                }
            }

            if (
                ! $rector instanceof MinPhpVersionInterface
                && ! $rector instanceof DeprecatedAtVersionInterface
            ) {
                $activeRectors[] = $rector;
                continue;
            }

            $deprecationVersion = $rector instanceof DeprecatedAtVersionInterface
                ? $rector->provideDeprecatedAtVersion()
                : null;
            $minPhpVersion = $rector instanceof MinPhpVersionInterface
                ? $rector->provideMinPhpVersion()
                : null;

            $requiredPhpVersion = (! $deprecationVersion || $isEagerlyResolveDeprecations)
                ? $minPhpVersion
                : $deprecationVersion;

            // does satisfy version? → include
            if ($requiredPhpVersion === null || $requiredPhpVersion <= $minProjectPhpVersion) {
                $activeRectors[] = $rector;
            }
        }

        return $activeRectors;
    }
}
