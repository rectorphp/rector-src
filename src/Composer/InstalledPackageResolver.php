<?php

declare(strict_types=1);

namespace Rector\Composer;

use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Rector\Composer\ValueObject\InstalledPackage;
use Rector\Exception\ShouldNotHappenException;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Composer\InstalledPackageResolverTest
 */
final class InstalledPackageResolver
{
    /**
     * @var array<string, InstalledPackage[]>
     */
    private array $resolvedInstalledPackages = [];

    /**
     * @return InstalledPackage[]
     */
    public function resolve(string $projectDirectory): array
    {
        // cache
        if (isset($this->resolvedInstalledPackages[$projectDirectory])) {
            return $this->resolvedInstalledPackages[$projectDirectory];
        }

        Assert::directory($projectDirectory);

        $installedPackagesFilePath = $projectDirectory . '/vendor/composer/installed.json';
        if (! file_exists($installedPackagesFilePath)) {
            throw new ShouldNotHappenException(
                'The installed package json not found. Make sure you run `composer update` and "vendor/composer/installed.json" file exists'
            );
        }

        $installedPackageFileContents = FileSystem::read($installedPackagesFilePath);
        $installedPackagesFilePath = Json::decode($installedPackageFileContents, true);

        $installedPackages = $this->createInstalledPackages($installedPackagesFilePath['packages']);

        $this->resolvedInstalledPackages[$projectDirectory] = $installedPackages;

        return $installedPackages;
    }

    /**
     * @param mixed[] $packages
     * @return InstalledPackage[]
     */
    private function createInstalledPackages(array $packages): array
    {
        $installedPackages = [];

        foreach ($packages as $package) {
            $installedPackages[] = new InstalledPackage($package['name'], $package['version_normalized']);
        }

        return $installedPackages;
    }
}
