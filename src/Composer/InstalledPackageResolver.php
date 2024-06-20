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
     * @return InstalledPackage[]
     */
    public function resolve(string $projectDirectory): array
    {
        Assert::directory($projectDirectory);

        $installedPackagesFilePath = $projectDirectory . '/vendor/composer/installed.json';
        if (! file_exists($installedPackagesFilePath)) {
            throw new ShouldNotHappenException(
                'The installed package json not found. Make sure you run `composer update` and "vendor/composer/installed.json" file exists'
            );
        }

        $installedPackageFileContents = FileSystem::read($installedPackagesFilePath);
        $installedPackagesFilePath = Json::decode($installedPackageFileContents, true);

        $installedPackages = [];

        foreach ($installedPackagesFilePath['packages'] as $installedPackage) {
            $installedPackages[] = new InstalledPackage(
                $installedPackage['name'],
                $installedPackage['version_normalized']
            );
        }

        return $installedPackages;
    }
}
