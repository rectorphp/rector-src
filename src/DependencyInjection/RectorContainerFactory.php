<?php

declare(strict_types=1);

namespace Rector\Core\DependencyInjection;

use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Core\Kernel\RectorKernel;
use Rector\Core\Stubs\PHPStanStubLoader;
use Rector\Core\ValueObject\Bootstrap\BootstrapConfigs;
use Rector\Core\ValueObject\Configuration;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symplify\PackageBuilder\Console\Input\StaticInputDetector;

final class RectorContainerFactory
{
    /**
     * @param string[] $configFiles
     * @api
     */
    public function createFromConfigs(array $configFiles): ContainerInterface
    {
        // to override the configs without clearing cache
        $isDebug = StaticInputDetector::isDebug();

        $environment = $this->createEnvironment($configFiles);

        // mt_rand is needed to invalidate container cache in case of class changes to be registered as services
        $isPHPUnitRun = StaticPHPUnitEnvironment::isPHPUnitRun();
        if (! $isPHPUnitRun) {
            $environment .= random_int(0, 10000);
        }

        $phpStanStubLoader = new PHPStanStubLoader();
        $phpStanStubLoader->loadStubs();

        $rectorKernel = new RectorKernel($environment, $isDebug, $configFiles);
        $rectorKernel->boot();

        return $rectorKernel->getContainer();
    }

    public function createFromBootstrapConfigs(BootstrapConfigs $bootstrapConfigs): ContainerInterface
    {
        $container = $this->createFromConfigs($bootstrapConfigs->getConfigFiles());

        $mainConfigFile = $bootstrapConfigs->getMainConfigFile();
        if ($mainConfigFile !== null) {
            /** @var ChangedFilesDetector $changedFilesDetector */
            $changedFilesDetector = $container->get(ChangedFilesDetector::class);
            $changedFilesDetector->setFirstResolvedConfigFileInfo($mainConfigFile);
        }

        return $container;
    }

    /**
     * @see https://symfony.com/doc/current/components/dependency_injection/compilation.html#dumping-the-configuration-for-performance
     * @param string[] $configFiles
     */
    private function createEnvironment(array $configFiles): string
    {
        $configHashes = [];
        foreach ($configFiles as $configFile) {
            $configHashes[] = md5_file($configFile);
        }

        $configHashString = implode('', $configHashes);
        return sha1($configHashString);
    }
}
