<?php

declare(strict_types=1);

namespace Rector\Parallel;

use Symplify\EasyParallel\ScheduleFactory as EasyParallelScheduleFactory;
use Symplify\EasyParallel\ValueObject\Schedule;

/**
 * @see \Rector\Tests\Parallel\ScheduleFactoryTest
 */
final class ScheduleFactory
{
    public function __construct(
        private readonly EasyParallelScheduleFactory $easyParallelScheduleFactory,
    ) {
    }

    /**
     * @param array<string> $files
     */
    public function create(int $cpuCores, int $jobSize, int $maxNumberOfProcesses, array $files): Schedule
    {
        if ($maxNumberOfProcesses < 0) {
            $cpuCores = max(1, $cpuCores + $maxNumberOfProcesses);
            $maxNumberOfProcesses = 0;
        }

        if ($maxNumberOfProcesses === 0) {
            $maxNumberOfProcesses = $cpuCores;
        }

        return $this->easyParallelScheduleFactory->create($cpuCores, $jobSize, $maxNumberOfProcesses, $files);
    }
}
