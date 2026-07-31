<?php

declare(strict_types=1);

namespace Rector\Set\ValueObject;

use Composer\Semver\Semver;
use Nette\Utils\Strings;
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

    /**
     * A bare "10.0" version, that is turned into a "^10.0" constraint
     *
     * @see https://regex101.com/r/vTJXPU/1
     */
    private const string BARE_VERSION_REGEX = '#^\d+(\.\d+)*$#';

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
        $package = $installedPackages[$this->packageName] ?? null;

        if (! $package instanceof InstalledPackage) {
            return false;
        }

        return Semver::satisfies($package->getVersion(), $this->resolveVersionConstraint());
    }

    public function getName(): string
    {
        return $this->packageName . ' ' . $this->version;
    }

    /**
     * A bare version means "this major version", e.g. "10.0" is "^10.0". Anything else is used as is,
     * to allow a set that spans multiple major versions, e.g. ">=10.0" or ">=10.0 <13.0".
     */
    private function resolveVersionConstraint(): string
    {
        if (Strings::match($this->version, self::BARE_VERSION_REGEX) !== null) {
            return '^' . $this->version;
        }

        return $this->version;
    }
}
