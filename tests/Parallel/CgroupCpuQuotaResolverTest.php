<?php

declare(strict_types=1);

namespace Rector\Tests\Parallel;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\Parallel\CgroupCpuQuotaResolver;

final class CgroupCpuQuotaResolverTest extends TestCase
{
    private CgroupCpuQuotaResolver $cgroupCpuQuotaResolver;

    protected function setUp(): void
    {
        $this->cgroupCpuQuotaResolver = new CgroupCpuQuotaResolver();
    }

    #[DataProvider('provideCgroupV2Data')]
    public function testParseCgroupV2(string $cpuMax, ?int $expected): void
    {
        $this->assertSame($expected, $this->cgroupCpuQuotaResolver->parseCgroupV2($cpuMax));
    }

    #[DataProvider('provideCgroupV1Data')]
    public function testParseCgroupV1(string $quota, string $period, ?int $expected): void
    {
        $this->assertSame($expected, $this->cgroupCpuQuotaResolver->parseCgroupV1($quota, $period));
    }

    /**
     * @return Iterator<array{string, int|null}>
     */
    public static function provideCgroupV2Data(): Iterator
    {
        yield ['max 100000', null];
        yield ['100000 100000', 1];
        yield ['250000 100000', 3];
        yield ['50000 100000', 1];
        yield ["200000 100000\n", 2];
        yield ['garbage', null];
    }

    /**
     * @return Iterator<array{string, string, int|null}>
     */
    public static function provideCgroupV1Data(): Iterator
    {
        yield ['-1', '100000', null];
        yield ['100000', '100000', 1];
        yield ['300000', '100000', 3];
        yield ['150000', '100000', 2];
        yield ["200000\n", "100000\n", 2];
        yield ['100000', '0', null];
    }
}
