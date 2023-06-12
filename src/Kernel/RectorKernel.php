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
     * @param string[] $configFiles
     * @api used in tests
     */
    public function createBuilder(array $configFiles = []): ContainerBuilder
    {
        return $this->buildContainer($configFiles);
    }

    /**
     * @param string[] $configFiles
     * @api used in tests
     */
    public function createFromConfigs(array $configFiles): ContainerInterface
    {
        if ($configFiles === []) {
            return $this->buildContainer([]);
        }

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

        return $containerBuilderBuilder->build($configFiles);
    }
}
