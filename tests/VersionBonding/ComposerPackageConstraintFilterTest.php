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
        $rector = new NoInterfaceRector();
        $filtered = $this->composerPackageConstraintFilter->filter([$rector]);

        $this->assertCount(1, $filtered);
        $this->assertSame($rector, $filtered[0]);
    }

    public function testRectorWithSatisfiedConstraintIsIncluded(): void
    {
        $rector = new ComposerPackageConstraintRector('nikic/php-parser', '>=4.0.0');
        $filtered = $this->composerPackageConstraintFilter->filter([$rector]);

        $this->assertCount(1, $filtered);
        $this->assertSame($rector, $filtered[0]);
    }

    public function testRectorWithUnsatisfiedConstraintIsExcluded(): void
    {
        $rector = new ComposerPackageConstraintRector('nikic/php-parser', '>=999.0.0');
        $filtered = $this->composerPackageConstraintFilter->filter([$rector]);

        $this->assertCount(0, $filtered);
    }

    public function testRectorWithMissingPackageIsExcluded(): void
    {
        $rector = new ComposerPackageConstraintRector('non-existent/package', '>=1.0.0');
        $filtered = $this->composerPackageConstraintFilter->filter([$rector]);

        $this->assertCount(0, $filtered);
    }

    public function testRectorWithCaretConstraint(): void
    {
        $rector = new ComposerPackageConstraintRector('nikic/php-parser', '^5.0');
        $filtered = $this->composerPackageConstraintFilter->filter([$rector]);

        $this->assertCount(1, $filtered);
        $this->assertSame($rector, $filtered[0]);
    }

    public function testRectorWithLessThanConstraintExcludesNewerVersions(): void
    {
        $rector = new ComposerPackageConstraintRector('nikic/php-parser', '<1.0.0');
        $filtered = $this->composerPackageConstraintFilter->filter([$rector]);

        $this->assertCount(0, $filtered);
    }
}
