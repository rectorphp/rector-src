<?php

declare(strict_types=1);

namespace Rector\Tests\VersionBonding;

use PHPUnit\Framework\TestCase;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Php\PhpVersionProvider;
use Rector\Php\PolyfillPackagesProvider;
use Rector\Tests\VersionBonding\Fixture\NoInterfaceRector;
use Rector\Tests\VersionBonding\Fixture\PolyfillPhp83Rector;
use Rector\ValueObject\PhpVersion;
use Rector\ValueObject\PolyfillPackage;
use Rector\VersionBonding\PhpVersionedFilter;

final class PhpVersionedFilterTest extends TestCase
{
    private PhpVersionedFilter $phpVersionedFilter;

    protected function setUp(): void
    {
        $phpVersionProvider = new PhpVersionProvider();
        $polyfillPackagesProvider = new PolyfillPackagesProvider();

        $this->phpVersionedFilter = new PhpVersionedFilter($phpVersionProvider, $polyfillPackagesProvider);

        SimpleParameterProvider::setParameter(Option::POLYFILL_PACKAGES, [PolyfillPackage::PHP_83]);
    }

    protected function tearDown(): void
    {
        SimpleParameterProvider::setParameter(Option::POLYFILL_PACKAGES, []);
        SimpleParameterProvider::setParameter(Option::POLYFILL_CEILING_PHP_VERSION, 0);
    }

    public function testRectorWithoutInterfaceIsIncluded(): void
    {
        $noInterfaceRector = new NoInterfaceRector();
        $filtered = $this->phpVersionedFilter->filter([$noInterfaceRector]);

        $this->assertCount(1, $filtered);
        $this->assertSame($noInterfaceRector, $filtered[0]);
    }

    public function testPolyfilledRectorIsIncluded(): void
    {
        $polyfillPhp83Rector = new PolyfillPhp83Rector();
        $filtered = $this->phpVersionedFilter->filter([$polyfillPhp83Rector]);

        $this->assertCount(1, $filtered);
        $this->assertSame($polyfillPhp83Rector, $filtered[0]);
    }

    public function testPolyfilledRectorAbovePickedPhpSetsVersionIsSkipped(): void
    {
        SimpleParameterProvider::setParameter(Option::POLYFILL_CEILING_PHP_VERSION, PhpVersion::PHP_82);

        $filtered = $this->phpVersionedFilter->filter([new PolyfillPhp83Rector()]);
        $this->assertCount(0, $filtered);
    }

    public function testPolyfilledRectorBelowPickedPhpSetsVersionIsIncluded(): void
    {
        SimpleParameterProvider::setParameter(Option::POLYFILL_CEILING_PHP_VERSION, PhpVersion::PHP_83);

        $polyfillPhp83Rector = new PolyfillPhp83Rector();
        $filtered = $this->phpVersionedFilter->filter([$polyfillPhp83Rector]);

        $this->assertCount(1, $filtered);
        $this->assertSame($polyfillPhp83Rector, $filtered[0]);
    }
}
