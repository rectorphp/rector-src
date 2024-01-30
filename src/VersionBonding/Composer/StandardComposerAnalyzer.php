<?php

namespace Rector\VersionBonding\Composer;

use Composer\InstalledVersions;
use Composer\Semver\VersionParser;

class StandardComposerAnalyzer implements ComposerAnalyzer
{
    public function isInstalled($packageName, $includeDevRequirements = true): bool
    {
        return InstalledVersions::isInstalled($packageName, $includeDevRequirements);
    }

    public function satisfies(VersionParser $parser, $packageName, $constraint): bool
    {
        return InstalledVersions::satisfies($parser, $packageName, $constraint);
    }
}
