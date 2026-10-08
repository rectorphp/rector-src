<?php

declare(strict_types=1);

namespace Rector\Parallel;

use Fidry\CpuCoreCounter\CpuCoreCounter;
use Fidry\CpuCoreCounter\NumberOfCpuCoreNotFound;

final readonly class CpuCoreCountProvider
{
    private const int DEFAULT_CORE_COUNT = 2;

    public function __construct(
        private CgroupCpuQuotaResolver $cgroupCpuQuotaResolver
    ) {
    }

    public function provide(): int
    {
        try {
            $logicalCoreCount = new CpuCoreCounter()
                ->getCount();
        } catch (NumberOfCpuCoreNotFound) {
            return self::DEFAULT_CORE_COUNT;
        }

        // a container CPU quota caps usable cores below the host's logical count
        $cgroupCoreCount = $this->cgroupCpuQuotaResolver->resolve();
        if ($cgroupCoreCount === null) {
            return $logicalCoreCount;
        }

        return min($logicalCoreCount, $cgroupCoreCount);
    }
}
