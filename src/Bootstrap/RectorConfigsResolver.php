<?php

declare(strict_types=1);

namespace Rector\Bootstrap;

use Rector\ValueObject\Bootstrap\BootstrapConfigs;
use Webmozart\Assert\Assert;

final class RectorConfigsResolver
{
    public const string DEFAULT_CONFIG_FILE = 'rector.php';

    public const string DEFAULT_DIST_CONFIG_FILE = 'rector.dist.php';

    public function provide(): BootstrapConfigs
    {
        $mainConfigFile = $this->resolveFromInputWithFallback();

        return new BootstrapConfigs($mainConfigFile, []);
    }

    private function resolveFromInput(): ?string
    {
        $configFile = CliOptionValueResolver::resolve($_SERVER['argv'] ?? [], ['--config', '-c']);
        if ($configFile === null) {
            return null;
        }

        Assert::fileExists($configFile);

        return realpath($configFile);
    }

    private function resolveFromInputWithFallback(): ?string
    {
        $configFile = $this->resolveFromInput();
        if ($configFile !== null) {
            return $configFile;
        }

        // Try rector.php first, then fall back to rector.dist.php
        $rectorConfigFile = $this->createFallbackFileInfoIfFound(self::DEFAULT_CONFIG_FILE);
        if ($rectorConfigFile !== null) {
            return $rectorConfigFile;
        }

        return $this->createFallbackFileInfoIfFound(self::DEFAULT_DIST_CONFIG_FILE);
    }

    private function createFallbackFileInfoIfFound(string $fallbackFile): ?string
    {
        $rootFallbackFile = getcwd() . DIRECTORY_SEPARATOR . $fallbackFile;
        if (! is_file($rootFallbackFile)) {
            return null;
        }

        return $rootFallbackFile;
    }
}
