<?php

declare(strict_types=1);

namespace Rector\Tests\Composer;

use PHPUnit\Framework\Attributes\DataProvider;
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

    #[DataProvider('provideLibraryTypeFixtureDirectory')]
    public function testLibraryTargetsLowestDeclaredVersionEvenWhenInstalledSatisfies(string $fixtureDirectory): void
    {
        $installedPackageResolver = new InstalledPackageResolver($fixtureDirectory);

        // installed 12.1.0 satisfies "^10.5 || ^11.0 || ^12.0", yet a library targets the lowest declared version
        $this->assertSame('10.5.0.0', $installedPackageResolver->resolvePackageVersion('phpunit/phpunit'));

        // installed 7.2.0 satisfies "^7.0", still lowered to the declared floor
        $this->assertSame('7.0.0.0', $installedPackageResolver->resolvePackageVersion('symfony/console'));

        // require-dev is respected as well
        $this->assertSame('3.2.0.0', $installedPackageResolver->resolvePackageVersion('nette/utils'));

        // not required in the "composer.json", the installed version stands
        $this->assertSame('1.11.0.0', $installedPackageResolver->resolvePackageVersion('webmozart/assert'));
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideLibraryTypeFixtureDirectory(): iterable
    {
        // "library" is the default distributed type
        yield 'library' => [__DIR__ . '/Fixture/InstalledPackageResolver/library_composer_json'];

        // any other non-"project" distributed type behaves the same way
        yield 'symfony-bundle' => [__DIR__ . '/Fixture/InstalledPackageResolver/symfony_bundle_composer_json'];
    }

    public function testProjectTypeKeepsInstalledVersionOverDeclaredRange(): void
    {
        $installedPackageResolver = new InstalledPackageResolver(
            __DIR__ . '/Fixture/InstalledPackageResolver/project_composer_json'
        );

        // an explicit "project" is an application, so the installed version stands even within a wider range
        $this->assertSame('12.1.0.0', $installedPackageResolver->resolvePackageVersion('phpunit/phpunit'));

        // installed 7.2.0 satisfies "^7.0", kept as installed
        $this->assertSame('7.2.0.0', $installedPackageResolver->resolvePackageVersion('symfony/console'));
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
