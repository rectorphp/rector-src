<?php

declare(strict_types=1);

namespace Rector\Core\Kernel;

use Rector\Core\Config\Loader\ConfigureCallMergingLoaderFactory;
use Rector\Core\DependencyInjection\Collector\ConfigureCallValuesCollector;
use Rector\Core\DependencyInjection\CompilerPass\MergeImportedRectorConfigureCallValuesCompilerPass;
use Rector\Core\DependencyInjection\CompilerPass\RemoveSkippedRectorsCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ContainerBuilderBuilder
{
    /**
     * @param string[] $configFiles
     */
    public function build(array $configFiles): ContainerBuilder
    {
        $configureCallValuesCollector = new ConfigureCallValuesCollector();

        $configureCallMergingLoaderFactory = new ConfigureCallMergingLoaderFactory($configureCallValuesCollector);
        $containerBuilderFactory = new ContainerBuilderFactory($configureCallMergingLoaderFactory);

        $containerBuilder = $containerBuilderFactory->create($configFiles, [
            new RemoveSkippedRectorsCompilerPass(),
            // adds all merged configure() parameters to rector services
            new MergeImportedRectorConfigureCallValuesCompilerPass($configureCallValuesCollector),
        ]);

        // @see https://symfony.com/blog/new-in-symfony-4-4-dependency-injection-improvements-part-1
        $containerBuilder->setParameter('container.dumper.inline_factories', true);
        // to fix reincluding files again
        $containerBuilder->setParameter('container.dumper.inline_class_loader', false);

        $containerBuilder->compile();

        return $containerBuilder;
    }
}
