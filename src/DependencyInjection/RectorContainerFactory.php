<?php

declare(strict_types=1);

namespace Rector\DependencyInjection;

use Rector\Autoloading\BootstrapFilesIncluder;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Config\RectorConfig;
use Rector\NodeTypeResolver\DependencyInjection\PHPStanServicesFactory;
use Rector\ValueObject\Bootstrap\BootstrapConfigs;

final class RectorContainerFactory
{
    public function createFromBootstrapConfigs(BootstrapConfigs $bootstrapConfigs): RectorConfig
    {
        $rectorConfig = $this->createFromConfigs($bootstrapConfigs->getConfigFiles());

        $mainConfigFile = $bootstrapConfigs->getMainConfigFile();

        // only the main process checks the configuration: the parallel workers share its cache directory,
        // and a worker that clears the cache would delete files the other workers are writing
        if ($mainConfigFile !== null && ! $this->isWorkerRun()) {
            /** @var ChangedFilesDetector $changedFilesDetector */
            $changedFilesDetector = $rectorConfig->make(ChangedFilesDetector::class);
            $changedFilesDetector->setFirstResolvedConfigFileInfo($mainConfigFile);
        }

        /** @var BootstrapFilesIncluder $bootstrapFilesIncluder */
        $bootstrapFilesIncluder = $rectorConfig->get(BootstrapFilesIncluder::class);
        $bootstrapFilesIncluder->includeBootstrapFiles($rectorConfig->get(PHPStanServicesFactory::class)->getContainer());

        return $rectorConfig;
    }

    /**
     * @param string[] $configFiles
     */
    private function createFromConfigs(array $configFiles): RectorConfig
    {
        $lazyContainerFactory = new LazyContainerFactory();
        $rectorConfig = $lazyContainerFactory->create();

        foreach ($configFiles as $configFile) {
            $rectorConfig->import($configFile);
        }

        $rectorConfig->boot();

        return $rectorConfig;
    }

    /**
     * The parallel workers are launched with "worker" as the first CLI argument.
     */
    private function isWorkerRun(): bool
    {
        foreach (array_slice($_SERVER['argv'] ?? [], 1) as $arg) {
            if (! is_string($arg) || str_starts_with($arg, '-')) {
                continue;
            }

            return $arg === 'worker';
        }

        return false;
    }
}
