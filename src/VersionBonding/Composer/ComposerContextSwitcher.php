<?php

namespace Rector\VersionBonding\Composer;

use Composer\InstalledVersions;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Webmozart\Assert\Assert;

class ComposerContextSwitcher
{
    protected ?array $originalDependencies = null;

    protected ?array $targetDependencies = null;

    public function loadTargetDependencies(): void
    {
        $vendorPath = SimpleParameterProvider::provideStringParameter(Option::VENDOR_PATH, getcwd() . '/vendor');

        Assert::fileExists($vendorPath . '/composer/installed.php');
        $dependencies = require $vendorPath . '/composer/installed.php';

        Assert::isArray($dependencies);
        $this->targetDependencies = $dependencies;
    }

    public function setComposerToTargetDependencies(): void
    {
        if ($this->targetDependencies === null) {
            throw new \RuntimeException('Target dependencies must be loaded first before setting them can be called.');
        }
        $this->originalDependencies = InstalledVersions::getAllRawData();
        InstalledVersions::reload($this->targetDependencies);
    }

    public function reset(): void
    {
        if ($this->originalDependencies === null) {
            throw new \RuntimeException('Target must be loaded first before reset can be called.');
        }
        InstalledVersions::reload($this->originalDependencies);
        $this->originalDependencies = null;
    }

    public function hasTargetDependencies(): bool
    {
        return $this->originalDependencies !== null;
    }
}
