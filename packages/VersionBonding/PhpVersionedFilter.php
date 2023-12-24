<?php

declare(strict_types=1);

namespace Rector\VersionBonding;

use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\Php\PolyfillPackagesProvider;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Rector\VersionBonding\Contract\RelatedPolyfillInterface;

final class PhpVersionedFilter
{
    public function __construct(
        private readonly PhpVersionProvider $phpVersionProvider,
        private readonly PolyfillPackagesProvider $polyfillPackagesProvider,
    ) {
    }

    /**
     * @param array<RectorInterface> $rectors
     * @return array<RectorInterface>
     */
    public function filter(iterable $rectors): array
    {
        $minProjectPhpVersion = $this->phpVersionProvider->provide();

        $activeRectors = [];
        foreach ($rectors as $rector) {
            if ($rector instanceof RelatedPolyfillInterface) {
                $polyfillPackageNames = $this->polyfillPackagesProvider->provide();

                if (in_array($rector->providePolyfillPackage(), $polyfillPackageNames, true)) {
                    $activeRectors[] = $rector;
                    continue;
                }
            }

            if (! $rector instanceof MinPhpVersionInterface) {
                $activeRectors[] = $rector;
                continue;
            }

            // does satisfy version? → include
            if ($rector->provideMinPhpVersion() <= $minProjectPhpVersion) {
                $activeRectors[] = $rector;
            }
        }

        return $activeRectors;
    }
}
