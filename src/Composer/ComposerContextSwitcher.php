<?php

namespace Rector\Composer;

use Composer\InstalledVersions;
use Webmozart\Assert\Assert;

class ComposerContextSwitcher
{
    protected ?array $originalDependencies = null;
    protected ?array $targetDependencies = null;

    public function __construct(protected string $vendorPath)
    {
    }

    public function loadTargetDependencies(): void
    {
        Assert::fileExists($this->vendorPath . '/composer/installed.php');
        $dependencies = require $this->vendorPath . '/composer/installed.php';

        Assert::isArray($dependencies);
        $this->targetDependencies = $dependencies;
    }

    public function setComposerToTargetDependencies(): void
    {
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
