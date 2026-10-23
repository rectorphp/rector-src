<?php

declare(strict_types=1);

namespace Rector\Parallel\ValueObject;

/**
 * Filled by @see \Rector\Parallel\ScheduleFactory. Jobs are not a shared pool: every worker owns its
 * own queue, decided up front.
 */
final readonly class BucketSchedule
{
    /**
     * @param array<int, array<int, array<string>>> $jobsPerWorker
     */
    public function __construct(
        private array $jobsPerWorker
    ) {
    }

    /**
     * @return array<int, array<int, array<string>>>
     */
    public function getJobsPerWorker(): array
    {
        return $this->jobsPerWorker;
    }
}
