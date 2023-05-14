<?php

declare(strict_types=1);

namespace Rector\Core\Kernel;

use Rector\Core\Config\Loader\ConfigureCallMergingLoaderFactory;
use Rector\Core\DependencyInjection\Collector\ConfigureCallValuesCollector;
use Rector\Core\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use Rector\Core\DependencyInjection\CompilerPass\AutowireRectorCompilerPass;
use Rector\Core\DependencyInjection\CompilerPass\MakeRectorsPublicCompilerPass;
use Rector\Core\DependencyInjection\CompilerPass\MergeImportedRectorConfigureCallValuesCompilerPass;
use Rector\Core\DependencyInjection\CompilerPass\RemoveSkippedRectorsCompilerPass;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Util\FileHasher;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symplify\SmartFileSystem\SmartFileSystem;
use Webmozart\Assert\Assert;

final class RectorKernel
{
    /**
     * @var string
     */
    private const CACHE_KEY = 'kernel-v6';

    private ContainerInterface|null $container = null;

    private bool $dumpFileCache = false;

    /**
     * @var string|null
     */
    private static $defaultFilesHash;

    public function __construct()
    {
        // while running tests we use different DI containers a lot,
        // therefore make sure we don't compile them over and over again.
        if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
            $this->dumpFileCache = true;
        }
    }

    /**
     * @api used in tests
     *
     * @param string[] $configFiles
     */
    public function createBuilder(array $configFiles = []): ContainerBuilder
    {
        return $this->buildContainer($configFiles);
    }

    /**
     * @api used in tests
     *
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles): ContainerInterface
    {
        if ($configFiles === []) {
            return $this->buildContainer([]);
        }

        if ($this->dumpFileCache) {
            $container = $this->buildCachedContainer($configFiles);
        } else {
            $container = $this->buildContainer($configFiles);
        }

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
    private function createConfigsHash(array $configFiles): string
    {
        $fileHasher = new FileHasher();

        if (self::$defaultFilesHash === null) {
            self::$defaultFilesHash = $fileHasher->hashFiles($this->createDefaultConfigFiles());
        }

        Assert::allString($configFiles);
        $configHash = $fileHasher->hashFiles($configFiles);

        return self::$defaultFilesHash . $configHash;
    }

    /**
     * @param string[] $configFiles
     */
    private function buildContainer(array $configFiles): ContainerBuilder
    {
        $defaultConfigFiles = $this->createDefaultConfigFiles();
        $configFiles = array_merge($defaultConfigFiles, $configFiles);

        $builder = new ContainerBuilderBuilder();
        return $this->container = $builder->build($configFiles);
    }

    /**
     * @param string[] $configFiles
     */
    private function buildCachedContainer(array $configFiles): ContainerInterface {
        $hash = $this->createConfigsHash($configFiles);

        $filesystem = new SmartFileSystem();
        $className = 'RectorKernel'.$hash;
        $file = sys_get_temp_dir() .'/rector/'. self::CACHE_KEY .'-'.$hash.'.php';

        if (file_exists($file)) {
            require_once $file;
            $className = '\\'.__NAMESPACE__ .'\\'. $className;
            $container = new $className();
            if (!$container instanceof ContainerInterface) {
                throw new ShouldNotHappenException();
            }
        } else {
            $container = $this->buildContainer($configFiles);

            $dumper = new PhpDumper($container);
            $dumpedContainer = $dumper->dump([
                'class' => $className,
                'namespace' => __NAMESPACE__
            ]);
            if (!is_string($dumpedContainer)) {
                throw new ShouldNotHappenException();
            }

            $filesystem->dumpFile($file, $dumpedContainer);
        }

        return $container;
    }

    static public function clearCache(): void {
        $dir = sys_get_temp_dir();
        if (!is_writable($dir)) {
            return;
        }

        $cacheFiles = glob($dir .'/rector/kernel-*.php');
        if ($cacheFiles === false) {
            return;
        }

        $smartFileSystem = new SmartFileSystem();
        $smartFileSystem->remove($cacheFiles);
    }

}
