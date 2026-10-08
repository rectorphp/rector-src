<?php

declare(strict_types=1);

namespace Rector\Parallel;

/**
 * Resolves the CPU quota a container (Docker, Kubernetes, systemd) imposes via cgroups.
 * Logical core count alone over-counts inside a quota, so Rector would fork more workers
 * than the quota allows. Inspired by PHPStan's cgroup-aware core detection.
 *
 * @see \Rector\Tests\Parallel\CgroupCpuQuotaResolverTest
 */
final class CgroupCpuQuotaResolver
{
    private const string CGROUP_V2_CPU_MAX = '/sys/fs/cgroup/cpu.max';

    private const string CGROUP_V1_QUOTA = '/sys/fs/cgroup/cpu/cpu.cfs_quota_us';

    private const string CGROUP_V1_PERIOD = '/sys/fs/cgroup/cpu/cpu.cfs_period_us';

    /**
     * Max cores the current cgroup quota allows, or null when there is no quota.
     */
    public function resolve(): ?int
    {
        if (is_file(self::CGROUP_V2_CPU_MAX)) {
            $cpuMax = file_get_contents(self::CGROUP_V2_CPU_MAX);
            if (is_string($cpuMax)) {
                return $this->parseCgroupV2($cpuMax);
            }
        }

        if (is_file(self::CGROUP_V1_QUOTA) && is_file(self::CGROUP_V1_PERIOD)) {
            $quota = file_get_contents(self::CGROUP_V1_QUOTA);
            $period = file_get_contents(self::CGROUP_V1_PERIOD);
            if (is_string($quota) && is_string($period)) {
                return $this->parseCgroupV1($quota, $period);
            }
        }

        return null;
    }

    /**
     * cgroup v2 "cpu.max" holds "$quota $period", or "max $period" when unlimited.
     */
    public function parseCgroupV2(string $cpuMax): ?int
    {
        $parts = preg_split('#\s+#', trim($cpuMax));
        if ($parts === false || count($parts) < 2) {
            return null;
        }

        if ($parts[0] === 'max') {
            return null;
        }

        return $this->toCoreCount((int) $parts[0], (int) $parts[1]);
    }

    /**
     * cgroup v1 keeps quota and period in separate files; a quota of -1 means unlimited.
     */
    public function parseCgroupV1(string $quota, string $period): ?int
    {
        $quotaMicroseconds = (int) trim($quota);
        if ($quotaMicroseconds <= 0) {
            return null;
        }

        return $this->toCoreCount($quotaMicroseconds, (int) trim($period));
    }

    private function toCoreCount(int $quotaMicroseconds, int $periodMicroseconds): ?int
    {
        if ($quotaMicroseconds <= 0 || $periodMicroseconds <= 0) {
            return null;
        }

        return max(1, (int) ceil($quotaMicroseconds / $periodMicroseconds));
    }
}
