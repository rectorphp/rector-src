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

    public function testStandaloneComposerJsonResolvesVersionsWithoutVendor(): void
    {
        $installedPackageResolver = new InstalledPackageResolver(
            null,
            __DIR__ . '/Fixture/InstalledPackageResolver/standalone_composer_json/composer.json'
        );

        // the package is not installed anywhere, the constraint is the only source
        $this->assertSame('2.6.0.0', $installedPackageResolver->resolvePackageVersion('symfony/framework-bundle'));

        // require-dev is respected as well
        $this->assertSame('10.5.0.0', $installedPackageResolver->resolvePackageVersion('phpunit/phpunit'));

        // an open constraint has no lowest version to fall back to
        $this->assertNull($installedPackageResolver->resolvePackageVersion('webmozart/assert'));

        // not required in the "composer.json" at all
        $this->assertNull($installedPackageResolver->resolvePackageVersion('symfony/console'));
    }

    public function testChangeComposerJsonFilePathDropsPreviousVersions(): void
    {
        $installedPackageResolver = new InstalledPackageResolver(getcwd());
        $this->assertNull($installedPackageResolver->resolvePackageVersion('symfony/framework-bundle'));

        $installedPackageResolver->changeComposerJsonFilePath(
            __DIR__ . '/Fixture/InstalledPackageResolver/standalone_composer_json/composer.json'
        );
        $this->assertSame('2.6.0.0', $installedPackageResolver->resolvePackageVersion('symfony/framework-bundle'));

        $installedPackageResolver->changeComposerJsonFilePath(null);
        $this->assertNull($installedPackageResolver->resolvePackageVersion('symfony/framework-bundle'));
    }
}
