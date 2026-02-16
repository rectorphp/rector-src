<?php

declare(strict_types=1);

namespace Rector\Tests\VersionBonding;

use PHPUnit\Framework\TestCase;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Php\PhpVersionProvider;
use Rector\Php\PolyfillPackagesProvider;
use Rector\Tests\VersionBonding\Fixture\DeprecatedAtVersionRector;
use Rector\Tests\VersionBonding\Fixture\MinPhpVersionRector;
use Rector\Tests\VersionBonding\Fixture\MixedVersionBoundsRector;
use Rector\Tests\VersionBonding\Fixture\NoInterfaceRector;
use Rector\ValueObject\PhpVersion;
use Rector\VersionBonding\PhpVersionedFilter;

final class PhpVersionedFilterTest extends TestCase
{
    private PhpVersionedFilter $phpVersionedFilter;

    protected function setUp(): void
    {
        $phpVersionProvider = new PhpVersionProvider();
        $polyfillPackagesProvider = new PolyfillPackagesProvider();

        $this->phpVersionedFilter = new PhpVersionedFilter($phpVersionProvider, $polyfillPackagesProvider);

        // Convenient Defaults
        SimpleParameterProvider::setParameter(Option::EAGERLY_RESOLVE_DEPRECATIONS, false);
        SimpleParameterProvider::setParameter(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_82);
    }

    protected function tearDown(): void
    {
        SimpleParameterProvider::unsetParameter(Option::EAGERLY_RESOLVE_DEPRECATIONS);
        SimpleParameterProvider::unsetParameter(Option::PHP_VERSION_FEATURES);
    }

    public function testRectorWithoutInterfaceIsIncluded(): void
    {
        $rectors = [new NoInterfaceRector()];
        $this->assertSame($rectors, $this->phpVersionedFilter->filter($rectors));
    }

    public function testMinPhpVersionRuleIsIncludedWhenVersionMatches(): void
    {
        $rectors = [new MinPhpVersionRector(PhpVersion::PHP_82), new MinPhpVersionRector(PhpVersion::PHP_81)];

        $this->assertSame($rectors, $this->phpVersionedFilter->filter($rectors));
    }

    public function testMinPhpVersionRuleIsExcludedWhenVersionDoesNotMatch(): void
    {
        $this->assertEmpty($this->phpVersionedFilter->filter([new MinPhpVersionRector(PhpVersion::PHP_84)]));
    }

    public function testDeprecatedAtVersionRuleIsIncludedWhenVersionMatches(): void
    {
        $rectors = [
            new DeprecatedAtVersionRector(PhpVersion::PHP_82),
            new DeprecatedAtVersionRector(PhpVersion::PHP_81),
        ];

        $this->assertSame($rectors, $this->phpVersionedFilter->filter($rectors));
    }

    public function testDeprecatedAtVersionRuleIsExcludedWhenVersionDoesNotMatch(): void
    {
        $this->assertEmpty($this->phpVersionedFilter->filter([new DeprecatedAtVersionRector(PhpVersion::PHP_84)]));
    }

    public function testRuleIsFilteredByDeprecatedAtVersionByDefault(): void
    {
        SimpleParameterProvider::unsetParameter(Option::EAGERLY_RESOLVE_DEPRECATIONS);

        $this->assertEmpty(
            $this->phpVersionedFilter->filter([
                new MixedVersionBoundsRector(PhpVersion::PHP_71, PhpVersion::PHP_84),
            ])
        );
    }

    public function testRuleIsFilteredByMinVersionInEagerMode(): void
    {
        SimpleParameterProvider::setParameter(Option::EAGERLY_RESOLVE_DEPRECATIONS, true);

        $rectors = [
            new DeprecatedAtVersionRector(PhpVersion::PHP_84),
            new MixedVersionBoundsRector(PhpVersion::PHP_71, PhpVersion::PHP_84),
        ];
        $this->assertSame($rectors, $this->phpVersionedFilter->filter($rectors));
    }

    public function testOtherRectorsAreUnaffectedByEagerMode(): void
    {
        SimpleParameterProvider::setParameter(Option::EAGERLY_RESOLVE_DEPRECATIONS, true);

        $includedRectors = [
            new NoInterfaceRector(),
            new MinPhpVersionRector(PhpVersion::PHP_82),
            new DeprecatedAtVersionRector(PhpVersion::PHP_82),
        ];
        $excludedRectors = [new MinPhpVersionRector(PhpVersion::PHP_84)];
        $rectors = [...$includedRectors, ...$excludedRectors];

        $this->assertSame($includedRectors, $this->phpVersionedFilter->filter($rectors));
    }

    public function testMixedRuleIsExcludedIfVersionDoesNotMatchEitherMinAndDeprecatedVersions(): void
    {
        $rectors = [new MixedVersionBoundsRector(PhpVersion::PHP_84, PhpVersion::PHP_84)];

        SimpleParameterProvider::setParameter(Option::EAGERLY_RESOLVE_DEPRECATIONS, true);
        $this->assertEmpty($this->phpVersionedFilter->filter($rectors));

        SimpleParameterProvider::setParameter(Option::EAGERLY_RESOLVE_DEPRECATIONS, false);
        $this->assertEmpty($this->phpVersionedFilter->filter($rectors));
    }

    public function testKeepInOriginalOrder(): void
    {
        $noInterfaceRector = new NoInterfaceRector();
        $excludedMinPhpVersionRector = new MinPhpVersionRector(PhpVersion::PHP_85);
        $includedMinPhpVersionRector = new MinPhpVersionRector(PhpVersion::PHP_82);
        $excludedDeprecatedAtVersionRector = new DeprecatedAtVersionRector(PhpVersion::PHP_85);
        $includedDeprecatedAtVersionRector = new DeprecatedAtVersionRector(PhpVersion::PHP_82);
        $excludedMixedVersionBoundsRector = new MixedVersionBoundsRector(PhpVersion::PHP_85, PhpVersion::PHP_85);
        $includedMixedVersionBoundsRector = new MixedVersionBoundsRector(PhpVersion::PHP_81, PhpVersion::PHP_81);

        $rectors = [
            $noInterfaceRector,
            $excludedMinPhpVersionRector,
            $includedMinPhpVersionRector,
            $excludedDeprecatedAtVersionRector,
            $includedDeprecatedAtVersionRector,
            $excludedMixedVersionBoundsRector,
            $includedMixedVersionBoundsRector,
        ];

        $this->assertSame(
            [
                $noInterfaceRector,
                $includedMinPhpVersionRector,
                $includedDeprecatedAtVersionRector,
                $includedMixedVersionBoundsRector,
            ],
            $this->phpVersionedFilter->filter($rectors)
        );
    }
}
