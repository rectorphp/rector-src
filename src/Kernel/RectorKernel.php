<?php

declare(strict_types=1);

namespace Rector\Core\Kernel;

use Rector\Core\Exception\ShouldNotHappenException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class RectorKernel
{
    private ContainerInterface|null $container = null;

    /**
     * @api used in tests
     */
    public function create(): ContainerBuilder
    {
        return $this->createFromConfigs([]);
    }

    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles): ContainerInterface
    {
        $container = $this->buildContainer($configFiles);
        return $this->container = $container;
    }

    /**
     * @api used in tests
     */
    public function getContainer(): ContainerInterface
    {
        if (! $this->container instanceof ContainerInterface) {
            throw new ShouldNotHappenException();
        }

        return $this->container;
    }

    /**
     * @return string[]
     */
    private function createDefaultConfigFiles(): array
    {
        return [__DIR__ . '/../../config/config.php'];
    }

    /**
     * @param string[] $configFiles
     */
    private function buildContainer(array $configFiles): ContainerBuilder
    {
        $defaultConfigFiles = $this->createDefaultConfigFiles();
        $configFiles = array_merge($defaultConfigFiles, $configFiles);

        $containerBuilderBuilder = new ContainerBuilderBuilder();

        return $this->container = $containerBuilderBuilder->build($configFiles);
    }
}
