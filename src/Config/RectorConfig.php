<?php

declare(strict_types=1);

namespace Rector\Config;

use Illuminate\Container\Container;
use PHPStan\Collectors\Collector;
use Rector\Caching\Contract\ValueObject\Storage\CacheStorageInterface;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Contract\Rector\RectorInterface;
use Rector\DependencyInjection\Laravel\ContainerMemento;
use Rector\Exception\ShouldNotHappenException;
use Rector\Skipper\SkipCriteriaResolver\SkippedClassResolver;
use Rector\Validation\RectorConfigValidator;
use Rector\ValueObject\PhpVersion;
use Rector\ValueObject\PolyfillPackage;
use Symfony\Component\Console\Command\Command;
use Webmozart\Assert\Assert;

/**
 * @api
 */
final class RectorConfig extends Container
{
    /**
     * @var array<class-string<RectorInterface>, mixed[]>>
     */
    private array $ruleConfigurations = [];

    /**
     * @var string[]
     */
    private array $autotagInterfaces = [Command::class];

    /**
     * @param string[] $paths
     */
    public function paths(array $paths): void
    {
        Assert::allString($paths);

        // ensure paths exist
        foreach ($paths as $path) {
            if (str_contains($path, '*')) {
                continue;
            }

            Assert::fileExists($path);
        }

        SimpleParameterProvider::setParameter(Option::PATHS, $paths);
    }

    /**
     * @param string[] $sets
     */
    public function sets(array $sets): void
    {
        Assert::allString($sets);

        foreach ($sets as $set) {
            Assert::fileExists($set);
            $this->import($set);
        }

        // for cache invalidation in case of sets change
        SimpleParameterProvider::addParameter(Option::REGISTERED_RECTOR_SETS, $sets);
    }

    public function disableParallel(): void
    {
        SimpleParameterProvider::setParameter(Option::PARALLEL, false);
    }

    /**
     * Defaults in sync with https://phpstan.org/config-reference#parallel-processing
     * as we run PHPStan as well
     */
    public function parallel(int $processTimeout = 120, int $maxNumberOfProcess = 32, int $jobSize = 20): void
    {
        SimpleParameterProvider::setParameter(Option::PARALLEL, true);
        SimpleParameterProvider::setParameter(Option::PARALLEL_JOB_TIMEOUT_IN_SECONDS, $processTimeout);
        SimpleParameterProvider::setParameter(Option::PARALLEL_MAX_NUMBER_OF_PROCESSES, $maxNumberOfProcess);
        SimpleParameterProvider::setParameter(Option::PARALLEL_JOB_SIZE, $jobSize);
    }

    public function noDiffs(): void
    {
        SimpleParameterProvider::setParameter(Option::NO_DIFFS, true);
    }

    public function memoryLimit(string $memoryLimit): void
    {
        SimpleParameterProvider::setParameter(Option::MEMORY_LIMIT, $memoryLimit);
    }

    /**
     * @see https://getrector.com/documentation/ignoring-rules-or-paths
     * @param array<int|string, mixed> $skip
     */
    public function skip(array $skip): void
    {
        RectorConfigValidator::ensureRectorRulesExist($skip);

        SimpleParameterProvider::addParameter(Option::SKIP, $skip);
    }

    public function removeUnusedImports(bool $removeUnusedImports = true): void
    {
        SimpleParameterProvider::setParameter(Option::REMOVE_UNUSED_IMPORTS, $removeUnusedImports);
    }

    public function importNames(bool $importNames = true, bool $importDocBlockNames = true): void
    {
        SimpleParameterProvider::setParameter(Option::AUTO_IMPORT_NAMES, $importNames);
        SimpleParameterProvider::setParameter(Option::AUTO_IMPORT_DOC_BLOCK_NAMES, $importDocBlockNames);
    }

    public function importShortClasses(bool $importShortClasses = true): void
    {
        SimpleParameterProvider::setParameter(Option::IMPORT_SHORT_CLASSES, $importShortClasses);
    }

    /**
     * Add PHPStan custom config to load extensions and custom configuration to Rector.
     */
    public function phpstanConfig(string $filePath): void
    {
        Assert::fileExists($filePath);
        SimpleParameterProvider::addParameter(Option::PHPSTAN_FOR_RECTOR_PATHS, [$filePath]);
    }

    /**
     * Add PHPStan custom configs to load extensions and custom configuration to Rector.
     *
     * @param string[] $filePaths
     */
    public function phpstanConfigs(array $filePaths): void
    {
        Assert::allString($filePaths);
        Assert::allFileExists($filePaths);
        SimpleParameterProvider::addParameter(Option::PHPSTAN_FOR_RECTOR_PATHS, $filePaths);
    }

    /**
     * @param class-string<ConfigurableRectorInterface&RectorInterface> $rectorClass
     * @param mixed[] $configuration
     */
    public function ruleWithConfiguration(string $rectorClass, array $configuration): void
    {
        Assert::classExists($rectorClass);
        Assert::isAOf($rectorClass, RectorInterface::class);
        Assert::isAOf($rectorClass, ConfigurableRectorInterface::class);

        // store configuration to cache
        $this->ruleConfigurations[$rectorClass] = array_merge(
            $this->ruleConfigurations[$rectorClass] ?? [],
            $configuration
        );

        $this->singleton($rectorClass);
        $this->tag($rectorClass, RectorInterface::class);

        $this->afterResolving($rectorClass, function (ConfigurableRectorInterface $configurableRector) use (
            $rectorClass
        ): void {
            $ruleConfiguration = $this->ruleConfigurations[$rectorClass];
            $configurableRector->configure($ruleConfiguration);
        });

        // for cache invalidation in case of sets change
        SimpleParameterProvider::addParameter(Option::REGISTERED_RECTOR_RULES, $rectorClass);
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    public function rule(string $rectorClass): void
    {
        Assert::classExists($rectorClass);
        Assert::isAOf($rectorClass, RectorInterface::class);

        $this->singleton($rectorClass);
        $this->tag($rectorClass, RectorInterface::class);

        // for cache invalidation in case of change
        SimpleParameterProvider::addParameter(Option::REGISTERED_RECTOR_RULES, $rectorClass);
    }

    /**
     * @param array<class-string<Collector>> $collectorClasses
     */
    public function collectors(array $collectorClasses): void
    {
        foreach ($collectorClasses as $collectorClass) {
            $this->collector($collectorClass);
        }
    }

    /**
     * @param class-string<Collector> $collectorClass
     */
    public function collector(string $collectorClass): void
    {
        trigger_error('collector have been deprecated as performance costly and not valuable');
    }

    /**
     * @param class-string<Command> $commandClass
     */
    public function command(string $commandClass): void
    {
        $this->singleton($commandClass);
        $this->tag($commandClass, Command::class);
    }

    public function import(string $filePath): void
    {
        if (str_contains($filePath, '*')) {
            throw new ShouldNotHappenException(
                'Matching file paths by using glob-patterns is no longer supported. Use specific file path instead.'
            );
        }

        Assert::fileExists($filePath);

        $self = $this;
        $callable = (require $filePath);

        Assert::isCallable($callable);
        /** @var callable(Container $container): void $callable */
        $callable($self);
    }

    /**
     * @param array<class-string<RectorInterface>> $rectorClasses
     */
    public function rules(array $rectorClasses): void
    {
        Assert::allString($rectorClasses);

        RectorConfigValidator::ensureNoDuplicatedClasses($rectorClasses);

        foreach ($rectorClasses as $rectorClass) {
            $this->rule($rectorClass);
        }
    }

    /**
     * @param PhpVersion::* $phpVersion
     */
    public function phpVersion(int $phpVersion): void
    {
        SimpleParameterProvider::setParameter(Option::PHP_VERSION_FEATURES, $phpVersion);
    }

    /**
     * @api only for testing. It is parsed from composer.json "require" packages by default
     * @param array<PolyfillPackage::*> $polyfillPackages
     */
    public function polyfillPackages(array $polyfillPackages): void
    {
        SimpleParameterProvider::setParameter(Option::POLYFILL_PACKAGES, $polyfillPackages);
    }

    /**
     * @param string[] $autoloadPaths
     */
    public function autoloadPaths(array $autoloadPaths): void
    {
        Assert::allString($autoloadPaths);

        SimpleParameterProvider::setParameter(Option::AUTOLOAD_PATHS, $autoloadPaths);
    }

    /**
     * @param string[] $bootstrapFiles
     */
    public function bootstrapFiles(array $bootstrapFiles): void
    {
        Assert::allString($bootstrapFiles);

        SimpleParameterProvider::setParameter(Option::BOOTSTRAP_FILES, $bootstrapFiles);
    }

    public function symfonyContainerXml(string $filePath): void
    {
        SimpleParameterProvider::setParameter(Option::SYMFONY_CONTAINER_XML_PATH_PARAMETER, $filePath);
    }

    public function symfonyContainerPhp(string $filePath): void
    {
        SimpleParameterProvider::setParameter(Option::SYMFONY_CONTAINER_PHP_PATH_PARAMETER, $filePath);
    }

    /**
     * @param string[] $extensions
     */
    public function fileExtensions(array $extensions): void
    {
        Assert::allString($extensions);

        SimpleParameterProvider::setParameter(Option::FILE_EXTENSIONS, $extensions);
    }

    public function cacheDirectory(string $directoryPath): void
    {
        // cache directory path is created via mkdir in CacheFactory
        // when not exists, so no need to validate $directoryPath is a directory
        SimpleParameterProvider::setParameter(Option::CACHE_DIR, $directoryPath);
    }

    public function containerCacheDirectory(string $directoryPath): void
    {
        // container cache directory path must be a directory on the first place
        Assert::directory($directoryPath);

        SimpleParameterProvider::setParameter(Option::CONTAINER_CACHE_DIRECTORY, $directoryPath);
    }

    /**
     * @param class-string<CacheStorageInterface> $cacheClass
     */
    public function cacheClass(string $cacheClass): void
    {
        Assert::isAOf($cacheClass, CacheStorageInterface::class);

        SimpleParameterProvider::setParameter(Option::CACHE_CLASS, $cacheClass);
    }

    /**
     * @see https://github.com/nikic/PHP-Parser/issues/723#issuecomment-712401963
     */
    public function indent(string $character, int $count): void
    {
        SimpleParameterProvider::setParameter(Option::INDENT_CHAR, $character);
        SimpleParameterProvider::setParameter(Option::INDENT_SIZE, $count);
    }

    /**
     * @internal
     * @api used only in tests
     */
    public function resetRuleConfigurations(): void
    {
        $this->ruleConfigurations = [];
    }

    /**
     * Compiler passes-like method
     */
    public function boot(): void
    {
        $skippedClassResolver = new SkippedClassResolver();
        $skippedElements = $skippedClassResolver->resolve();

        foreach ($skippedElements as $skippedClass => $path) {
            if ($path !== null) {
                continue;
            }

            // completely forget the Rector rule only when no path specified
            ContainerMemento::forgetService($this, $skippedClass);
        }
    }

    /**
     * @internal Use to add tag on service registrations
     */
    public function autotagInterface(string $interface): void
    {
        $this->autotagInterfaces[] = $interface;
    }

    /**
     * @param string $abstract
     */
    public function singleton($abstract, mixed $concrete = null): void
    {
        parent::singleton($abstract, $concrete);

        foreach ($this->autotagInterfaces as $autotagInterface) {
            if (! is_a($abstract, $autotagInterface, true)) {
                continue;
            }

            $this->tag($abstract, $autotagInterface);
        }
    }
}
