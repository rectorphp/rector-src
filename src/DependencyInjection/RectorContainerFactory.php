<?php

declare(strict_types=1);

namespace Rector\Core\DependencyInjection;

use Psr\Container\ContainerInterface;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Core\Autoloading\BootstrapFilesIncluder;
use Rector\Core\Kernel\RectorKernel;
use Rector\Core\ValueObject\Bootstrap\BootstrapConfigs;
use Rector\NodeTypeResolver\DependencyInjection\PHPStanServicesFactory;

final class RectorContainerFactory
{
    public function createFromBootstrapConfigs(BootstrapConfigs $bootstrapConfigs): ContainerInterface
    {
        $rectorKernel = new RectorKernel();
        $container = $rectorKernel->createBuilder($bootstrapConfigs->getConfigFiles());

        $mainConfigFile = $bootstrapConfigs->getMainConfigFile();

        if ($mainConfigFile !== null) {
            /** @var ChangedFilesDetector $changedFilesDetector */
            $changedFilesDetector = $container->get(ChangedFilesDetector::class);
            $changedFilesDetector->setFirstResolvedConfigFileInfo($mainConfigFile);
        }

        /** @var BootstrapFilesIncluder $bootstrapFilesIncluder */
        $bootstrapFilesIncluder = $container->get(BootstrapFilesIncluder::class);
        $bootstrapFilesIncluder->includeBootstrapFiles();

        $phpStanServicesFactory = $container->get(PHPStanServicesFactory::class);

        /** @var PHPStanServicesFactory $phpStanServicesFactory */
        $phpStanContainer = $phpStanServicesFactory->provideContainer();
        $bootstrapFilesIncluder->includePHPStanExtensionsBoostrapFiles($phpStanContainer);

        return $container;
    }
}
