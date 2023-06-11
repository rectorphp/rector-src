<?php

declare(strict_types=1);

namespace Rector\VersionBonding;

use Rector\Core\Contract\Rector\PhpRectorInterface;
use Rector\Core\Php\PhpVersionProvider;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;

final class PhpVersionedFilter
{
    public function __construct(
        private readonly PhpVersionProvider $phpVersionProvider
    ) {
    }

    /**
     * @param array<PhpRectorInterface> $rectors
     * @return array<PhpRectorInterface>
     */
    public function filter(iterable $rectors): array
    {
        $minProjectPhpVersion = $this->phpVersionProvider->provide();

        $activeRectors = [];
        foreach ($rectors as $rector) {
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
