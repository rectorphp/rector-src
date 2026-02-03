<?php

declare(strict_types=1);

namespace Rector\Set\ValueObject;

use Composer\Semver\Semver;
use Rector\Composer\ValueObject\InstalledPackage;
use Rector\Set\Contract\SetInterface;
use Webmozart\Assert\Assert;

/**
 * @api used by extensions
 */
final readonly class ComposerTriggeredSet implements SetInterface
{
    /**
     * @see https://regex101.com/r/ioYomu/1
     */
    private const string PACKAGE_REGEX = '#^[a-z0-9-]+\/([a-z0-9-_]+|\*)$#';

    public function __construct(
        private string $groupName,
        private string $packageName,
        private string $version,
        private string $setFilePath
    ) {
        Assert::regex($this->packageName, self::PACKAGE_REGEX);
        Assert::fileExists($setFilePath);
    }

    public function getGroupName(): string
    {
        return $this->groupName;
    }

    public function getSetFilePath(): string
    {
        return $this->setFilePath;
    }

    /**
     * @param array<string, InstalledPackage> $installedPackages
     */
    public function matchInstalledPackages(array $installedPackages): bool
    {
        $installedVersion = ($installedPackages[$this->packageName] ?? null)?->getVersion();

        if ($installedVersion === null) {
            return false;
        }

        return Semver::satisfies($installedVersion, '^' . $this->version);
    }

    public function getName(): string
    {
        return $this->packageName . ' ' . $this->version;
    }
}
