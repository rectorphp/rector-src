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
use Rector\Core\Util\Hasher;
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
    private const CACHE_KEY = 'kernel-v5';

    private readonly ConfigureCallValuesCollector $configureCallValuesCollector;

    private ContainerInterface|null $container = null;

    private bool $dumpFileCache = false;

    /**
     * @var string|null
     */
    private static $defaultFilesHash;

    public function __construct()
    {
        $this->configureCallValuesCollector = new ConfigureCallValuesCollector();

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
     * @return CompilerPassInterface[]
     */
    private function createCompilerPasses(): array
    {
        return [

            // must run before AutowireArrayParameterCompilerPass, as the autowired array cannot contain removed services
            new RemoveSkippedRectorsCompilerPass(),

            // autowire Rectors by default (mainly for tests)
            new AutowireRectorCompilerPass(),
            new MakeRectorsPublicCompilerPass(),

            // add all merged arguments of Rector services
            new MergeImportedRectorConfigureCallValuesCompilerPass($this->configureCallValuesCollector),
            new AutowireArrayParameterCompilerPass(),
        ];
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
        $fileHasher = new Hasher();

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
    private function buildContainer(array $configFiles, string $hash = null): ContainerBuilder
    {
        $defaultConfigFiles = $this->createDefaultConfigFiles();
        $configFiles = array_merge($defaultConfigFiles, $configFiles);

        $compilerPasses = $this->createCompilerPasses();

        $configureCallMergingLoaderFactory = new ConfigureCallMergingLoaderFactory($this->configureCallValuesCollector);
        $containerBuilderFactory = new ContainerBuilderFactory($configureCallMergingLoaderFactory);

        $containerBuilder = $containerBuilderFactory->create($configFiles, $compilerPasses);

        // @see https://symfony.com/blog/new-in-symfony-4-4-dependency-injection-improvements-part-1
        $containerBuilder->setParameter('container.dumper.inline_factories', true);
        // to fix reincluding files again
        $containerBuilder->setParameter('container.dumper.inline_class_loader', false);

        $containerBuilder->compile();

        return $this->container = $containerBuilder;
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
            $container = $this->buildContainer($configFiles, $hash);

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

}
