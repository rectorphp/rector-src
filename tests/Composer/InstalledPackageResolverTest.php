<?php

declare(strict_types=1);

namespace Rector\Tests\Composer;

use PHPUnit\Framework\TestCase;
use Rector\Composer\InstalledPackageResolver;
use Rector\Composer\ValueObject\InstalledPackage;

final class InstalledPackageResolverTest extends TestCase
{
    public function test(): void
    {
        $installedPackageResolver = new InstalledPackageResolver(getcwd());
        $installedPackages = $installedPackageResolver->resolve();

        $this->assertContainsOnlyInstancesOf(InstalledPackage::class, $installedPackages);
        $this->assertGreaterThan(77, count($installedPackages));
    }

    public function testFallbackToCurrentWorkingDirectory(): void
    {
        $installedPackageResolver = new InstalledPackageResolver();
        $installedPackages = $installedPackageResolver->resolve();

        $this->assertContainsOnlyInstancesOf(InstalledPackage::class, $installedPackages);
        $this->assertGreaterThan(77, count($installedPackages));
    }

    public function testComposerJsonHasPriorityOverOutdatedInstalledJson(): void
    {
        $installedPackageResolver = new InstalledPackageResolver(
            __DIR__ . '/Fixture/InstalledPackageResolver/outdated_installed_json'
        );

        // the "installed.json" is outdated and contains 11.5.2.0
        $this->assertSame('10.5.0.0', $installedPackageResolver->resolvePackageVersion('phpunit/phpunit'));

        // the installed version matches the "composer.json" constraint
        $this->assertSame('7.2.0.0', $installedPackageResolver->resolvePackageVersion('symfony/console'));

        // require-dev is respected as well
        $this->assertSame('3.2.0.0', $installedPackageResolver->resolvePackageVersion('nette/utils'));

        // not required in the "composer.json" at all
        $this->assertSame('1.11.0.0', $installedPackageResolver->resolvePackageVersion('webmozart/assert'));
    }
}
