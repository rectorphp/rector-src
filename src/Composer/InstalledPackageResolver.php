<?php

declare(strict_types=1);

namespace Rector\Composer;

use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Rector\Composer\ValueObject\InstalledPackage;
use Rector\Exception\ShouldNotHappenException;
use Rector\Skipper\FileSystem\PathNormalizer;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Composer\InstalledPackageResolverTest
 */
final class InstalledPackageResolver
{
    /**
     * @var InstalledPackage[]
     */
    private array $resolvedInstalledPackages = [];

    public function __construct(
        private readonly ?string $projectDirectory = null
    ) {
        // fallback to root project directory
        if ($projectDirectory === null) {
            $projectDirectory = getcwd();
        }

        Assert::directory($projectDirectory);
    }

    /**
     * @return InstalledPackage[]
     */
    public function resolve(): array
    {
        // cache
        if ($this->resolvedInstalledPackages !== []) {
            return $this->resolvedInstalledPackages;
        }

        $installedPackagesFilePath = self::resolveVendorDir() . '/composer/installed.json';
        if (! file_exists($installedPackagesFilePath)) {
            throw new ShouldNotHappenException(
                'The installed package json not found. Make sure you run `composer update` and the "vendor/composer/installed.json" file exists'
            );
        }

        $installedPackageFileContents = FileSystem::read($installedPackagesFilePath);
        $installedPackagesFilePath = Json::decode($installedPackageFileContents, true);

        $installedPackages = $this->createInstalledPackages($installedPackagesFilePath['packages']);

        $this->resolvedInstalledPackages = $installedPackages;

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

    private function resolveVendorDir(): string
    {
        $projectComposerJsonFilePath = $this->projectDirectory . '/composer.json';
        if (\file_exists($projectComposerJsonFilePath)) {
            $projectComposerContents = FileSystem::read($projectComposerJsonFilePath);
            $projectComposerJson = Json::decode($projectComposerContents, true);

            if (isset($projectComposerJson['config']['vendor-dir']) &&
                is_string($projectComposerJson['config']['vendor-dir'])
            ) {
                return PathNormalizer::normalize(
                    realpath($projectComposerJson['config']['vendor-dir'])
                ) === PathNormalizer::normalize($projectComposerJson['config']['vendor-dir'])
                    ? $projectComposerJson['config']['vendor-dir']
                    : $this->projectDirectory . '/' . $projectComposerJson['config']['vendor-dir'];
            }

        }
        return $this->projectDirectory . '/vendor';
    }
}
