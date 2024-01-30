<?php

namespace Rector\VersionBonding\Composer;

use Composer\Semver\VersionParser;

interface ComposerAnalyzer
{
    public function isInstalled($packageName, $includeDevRequirements = true): bool;

    public function satisfies(VersionParser $parser, $packageName, $constraint): bool;
}
