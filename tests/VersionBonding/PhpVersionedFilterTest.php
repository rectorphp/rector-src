<?php

declare(strict_types=1);

namespace Rector\Tests\VersionBonding;

use PHPUnit\Framework\TestCase;
use Rector\Php\PhpVersionProvider;
use Rector\Php\PolyfillPackagesProvider;
use Rector\Tests\VersionBonding\Fixture\NoInterfaceRector;
use Rector\VersionBonding\PhpVersionedFilter;

final class PhpVersionedFilterTest extends TestCase
{
    private PhpVersionedFilter $phpVersionedFilter;

    protected function setUp(): void
    {
        $phpVersionProvider = new PhpVersionProvider();
        $polyfillPackagesProvider = new PolyfillPackagesProvider();

        $this->phpVersionedFilter = new PhpVersionedFilter($phpVersionProvider, $polyfillPackagesProvider);
    }

    public function testRectorWithoutInterfaceIsIncluded(): void
    {
        $rector = new NoInterfaceRector();
        $filtered = $this->phpVersionedFilter->filter([$rector]);

        $this->assertCount(1, $filtered);
        $this->assertSame($rector, $filtered[0]);
    }
}
