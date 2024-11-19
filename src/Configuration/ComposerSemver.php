<?php

declare(strict_types=1);

namespace Rector\Configuration;

use Composer\Semver\Semver;
use Rector\Composer\InstalledPackageResolver;

/**
 * @see \Rector\Tests\Configuration\ComposerSemverTest
 * @api used by extensions
 */
final readonly class ComposerSemver
{
    public static function matchesPackageVersion(string $packageName, string $version): bool
    {
        $installedPackageResolver = new InstalledPackageResolver(getcwd());
        $installedComposerPackages = $installedPackageResolver->resolve();

        foreach ($installedComposerPackages as $installedComposerPackage) {
            if ($installedComposerPackage->getName() !== $packageName) {
                continue;
            }

            return Semver::satisfies($installedComposerPackage->getVersion(), '^' . $version);
        }

        return false;
    }
}
