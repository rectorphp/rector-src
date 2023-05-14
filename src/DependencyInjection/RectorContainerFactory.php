<?php

declare(strict_types=1);

namespace Rector\Core\DependencyInjection;

use Psr\Container\ContainerInterface;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Core\Autoloading\BootstrapFilesIncluder;
use Rector\Core\Kernel\RectorKernel;
use Rector\Core\ValueObject\Bootstrap\BootstrapConfigs;
use Rector\NodeTypeResolver\DependencyInjection\PHPStanServicesFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RectorContainerFactory
{
    public function createFromBootstrapConfigs(BootstrapConfigs $bootstrapConfigs): ContainerInterface
    {
        $containerBuilder = $this->createFromConfigs($bootstrapConfigs->getConfigFiles());

        $mainConfigFile = $bootstrapConfigs->getMainConfigFile();

        if ($mainConfigFile !== null) {
            /** @var ChangedFilesDetector $changedFilesDetector */
            $changedFilesDetector = $containerBuilder->get(ChangedFilesDetector::class);
            $changedFilesDetector->setFirstResolvedConfigFileInfo($mainConfigFile);
        }

        /** @var BootstrapFilesIncluder $bootstrapFilesIncluder */
        $bootstrapFilesIncluder = $containerBuilder->get(BootstrapFilesIncluder::class);
        $bootstrapFilesIncluder->includeBootstrapFiles();

        $phpStanServicesFactory = $containerBuilder->get(PHPStanServicesFactory::class);

        /** @var PHPStanServicesFactory $phpStanServicesFactory */
        $phpStanContainer = $phpStanServicesFactory->provideContainer();
        $bootstrapFilesIncluder->includePHPStanExtensionsBoostrapFiles($phpStanContainer);

        return $containerBuilder;
    }

    /**
     * @param string[] $configFiles
     * @api
     */
    private function createFromConfigs(array $configFiles): ContainerBuilder
    {
        $rectorKernel = new RectorKernel();
        return $rectorKernel->createBuilder($configFiles);
    }
}
