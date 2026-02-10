<?php

declare(strict_types=1);

namespace Rector\Tests\VersionBonding;

use PHPUnit\Framework\TestCase;
use Rector\Composer\InstalledPackageResolver;
use Rector\Tests\VersionBonding\Fixture\ComposerPackageConstraintRector;
use Rector\Tests\VersionBonding\Fixture\NoInterfaceRector;
use Rector\VersionBonding\ComposerPackageConstraintFilter;

final class ComposerPackageConstraintFilterTest extends TestCase
{
    private ComposerPackageConstraintFilter $composerPackageConstraintFilter;

    protected function setUp(): void
    {
        $installedPackageResolver = new InstalledPackageResolver(getcwd());

        $this->composerPackageConstraintFilter = new ComposerPackageConstraintFilter($installedPackageResolver);
    }

    public function testRectorWithoutInterfaceIsIncluded(): void
    {
        $noInterfaceRector = new NoInterfaceRector();
        $filtered = $this->composerPackageConstraintFilter->filter([$noInterfaceRector]);

        $this->assertCount(1, $filtered);
        $this->assertSame($noInterfaceRector, $filtered[0]);
    }

    public function testRectorWithSatisfiedConstraintIsIncluded(): void
    {
        $composerPackageConstraintRector = new ComposerPackageConstraintRector('nikic/php-parser', '>=4.0.0');
        $filtered = $this->composerPackageConstraintFilter->filter([$composerPackageConstraintRector]);

        $this->assertCount(1, $filtered);
        $this->assertSame($composerPackageConstraintRector, $filtered[0]);
    }

    public function testRectorWithUnsatisfiedConstraintIsExcluded(): void
    {
        $composerPackageConstraintRector = new ComposerPackageConstraintRector('nikic/php-parser', '>=999.0.0');
        $filtered = $this->composerPackageConstraintFilter->filter([$composerPackageConstraintRector]);

        $this->assertCount(0, $filtered);
    }

    public function testRectorWithMissingPackageIsExcluded(): void
    {
        $composerPackageConstraintRector = new ComposerPackageConstraintRector('non-existent/package', '>=1.0.0');
        $filtered = $this->composerPackageConstraintFilter->filter([$composerPackageConstraintRector]);

        $this->assertCount(0, $filtered);
    }

    public function testRectorWithCaretConstraint(): void
    {
        $composerPackageConstraintRector = new ComposerPackageConstraintRector('nikic/php-parser', '^5.0');
        $filtered = $this->composerPackageConstraintFilter->filter([$composerPackageConstraintRector]);

        $this->assertCount(1, $filtered);
        $this->assertSame($composerPackageConstraintRector, $filtered[0]);
    }

    public function testRectorWithLessThanConstraintExcludesNewerVersions(): void
    {
        $composerPackageConstraintRector = new ComposerPackageConstraintRector('nikic/php-parser', '<1.0.0');
        $filtered = $this->composerPackageConstraintFilter->filter([$composerPackageConstraintRector]);

        $this->assertCount(0, $filtered);
    }
}
