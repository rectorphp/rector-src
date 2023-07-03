<?php

declare(strict_types=1);

namespace Rector\Config;

use Rector\Caching\Contract\ValueObject\Storage\CacheStorageInterface;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\Core\Configuration\ValueObjectInliner;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Contract\Rector\NonPhpRectorInterface;
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\ValueObject\PhpVersion;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServiceConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Webmozart\Assert\Assert;

/**
 * @api
 * Same as Symfony container configurator, with patched return type for "set()" method for easier DX.
 * It is an alias for internal class that is prefixed during build, so it's basically for keeping stable public API.
 */
final class RectorConfig extends ContainerConfigurator
{
    private ?ServicesConfigurator $servicesConfigurator = null;

    /**
     * @param string[] $paths
     */
    public function paths(array $paths): void
    {
        Assert::allString($paths);

        $parameters = $this->parameters();
        $parameters->set(Option::PATHS, $paths);

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
    }

    public function disableParallel(): void
    {
        $parameters = $this->parameters();

        $parameters->set(Option::PARALLEL, false);
        SimpleParameterProvider::setParameter(Option::PARALLEL, false);
    }

    public function parallel(int $seconds = 120, int $maxNumberOfProcess = 16, int $jobSize = 20): void
    {
        $parameters = $this->parameters();

        $parameters->set(Option::PARALLEL, true);
        SimpleParameterProvider::setParameter(Option::PARALLEL, true);

        $parameters->set(Option::PARALLEL_JOB_TIMEOUT_IN_SECONDS, $seconds);
        SimpleParameterProvider::setParameter(Option::PARALLEL_JOB_TIMEOUT_IN_SECONDS, $seconds);

        $parameters->set(Option::PARALLEL_MAX_NUMBER_OF_PROCESSES, $maxNumberOfProcess);
        SimpleParameterProvider::setParameter(Option::PARALLEL_MAX_NUMBER_OF_PROCESSES, $maxNumberOfProcess);

        $parameters->set(Option::PARALLEL_JOB_SIZE, $jobSize);
        SimpleParameterProvider::setParameter(Option::PARALLEL_JOB_SIZE, $jobSize);
    }

    public function noDiffs(): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::NO_DIFFS, true);

        SimpleParameterProvider::setParameter(Option::NO_DIFFS, true);
    }

    public function memoryLimit(string $memoryLimit): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::MEMORY_LIMIT, $memoryLimit);

        SimpleParameterProvider::setParameter(Option::MEMORY_LIMIT, $memoryLimit);
    }

    /**
     * @param array<int|string, mixed> $criteria
     */
    public function skip(array $criteria): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::SKIP, $criteria);

        SimpleParameterProvider::addParameter(Option::SKIP, $criteria);
    }

    public function removeUnusedImports(bool $removeUnusedImports = true): void
    {
        SimpleParameterProvider::setParameter(Option::REMOVE_UNUSED_IMPORTS, $removeUnusedImports);
    }

    public function importNames(bool $importNames = true, bool $importDocBlockNames = true): void
    {
        $parameters = $this->parameters();

        $parameters->set(Option::AUTO_IMPORT_NAMES, $importNames);
        SimpleParameterProvider::setParameter(Option::AUTO_IMPORT_NAMES, $importNames);

        $parameters->set(Option::AUTO_IMPORT_DOC_BLOCK_NAMES, $importDocBlockNames);
        SimpleParameterProvider::setParameter(Option::AUTO_IMPORT_DOC_BLOCK_NAMES, $importDocBlockNames);
    }

    public function importShortClasses(bool $importShortClasses = true): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::IMPORT_SHORT_CLASSES, $importShortClasses);

        SimpleParameterProvider::setParameter(Option::IMPORT_SHORT_CLASSES, $importShortClasses);
    }

    /**
     * Set PHPStan custom config to load extensions and custom configuration to Rector.
     * By default, the "phpstan.neon" path is used.
     */
    public function phpstanConfig(string $filePath): void
    {
        Assert::fileExists($filePath);

        $parameters = $this->parameters();
        $parameters->set(Option::PHPSTAN_FOR_RECTOR_PATH, $filePath);

        SimpleParameterProvider::setParameter(Option::PHPSTAN_FOR_RECTOR_PATH, $filePath);
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

        // decorate with value object inliner so Symfony understands, see https://getrector.org/blog/2020/09/07/how-to-inline-value-object-in-symfony-php-config
        array_walk_recursive($configuration, static function (&$value) {
            if (is_object($value)) {
                $value = ValueObjectInliner::inline($value);
            }

            return $value;
        });

        $servicesConfigurator = $this->getServices();

        $rectorService = $servicesConfigurator->set($rectorClass)
            ->public()
            ->autowire()
            ->call('configure', [$configuration]);
        $this->tagRectorService($rectorService, $rectorClass);
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    public function rule(string $rectorClass): void
    {
        Assert::classExists($rectorClass);
        Assert::isAOf($rectorClass, RectorInterface::class);

        $servicesConfigurator = $this->getServices();

        $rectorService = $servicesConfigurator->set($rectorClass)
            ->public()
            ->autowire();

        $this->tagRectorService($rectorService, $rectorClass);
    }

    /**
     * @param array<class-string<RectorInterface>> $rectorClasses
     */
    public function rules(array $rectorClasses): void
    {
        Assert::allString($rectorClasses);
        Assert::uniqueValues($rectorClasses);

        foreach ($rectorClasses as $rectorClass) {
            $this->rule($rectorClass);
        }
    }

    /**
     * @param PhpVersion::* $phpVersion
     */
    public function phpVersion(int $phpVersion): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::PHP_VERSION_FEATURES, $phpVersion);

        SimpleParameterProvider::setParameter(Option::PHP_VERSION_FEATURES, $phpVersion);
    }

    /**
     * @param string[] $autoloadPaths
     */
    public function autoloadPaths(array $autoloadPaths): void
    {
        Assert::allString($autoloadPaths);

        $parameters = $this->parameters();
        $parameters->set(Option::AUTOLOAD_PATHS, $autoloadPaths);

        SimpleParameterProvider::setParameter(Option::AUTOLOAD_PATHS, $autoloadPaths);
    }

    /**
     * @param string[] $bootstrapFiles
     */
    public function bootstrapFiles(array $bootstrapFiles): void
    {
        Assert::allString($bootstrapFiles);

        $parameters = $this->parameters();
        $parameters->set(Option::BOOTSTRAP_FILES, $bootstrapFiles);

        SimpleParameterProvider::setParameter(Option::BOOTSTRAP_FILES, $bootstrapFiles);
    }

    public function symfonyContainerXml(string $filePath): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::SYMFONY_CONTAINER_XML_PATH_PARAMETER, $filePath);

        SimpleParameterProvider::setParameter(Option::SYMFONY_CONTAINER_XML_PATH_PARAMETER, $filePath);
    }

    public function symfonyContainerPhp(string $filePath): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::SYMFONY_CONTAINER_PHP_PATH_PARAMETER, $filePath);

        SimpleParameterProvider::setParameter(Option::SYMFONY_CONTAINER_PHP_PATH_PARAMETER, $filePath);
    }

    /**
     * @param string[] $extensions
     */
    public function fileExtensions(array $extensions): void
    {
        Assert::allString($extensions);

        $parameters = $this->parameters();
        $parameters->set(Option::FILE_EXTENSIONS, $extensions);

        SimpleParameterProvider::setParameter(Option::FILE_EXTENSIONS, $extensions);
    }

    public function nestedChainMethodCallLimit(int $limit): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::NESTED_CHAIN_METHOD_CALL_LIMIT, $limit);

        SimpleParameterProvider::setParameter(Option::NESTED_CHAIN_METHOD_CALL_LIMIT, $limit);
    }

    public function cacheDirectory(string $directoryPath): void
    {
        // cache directory path is created via mkdir in CacheFactory
        // when not exists, so no need to validate $directoryPath is a directory
        $parameters = $this->parameters();
        $parameters->set(Option::CACHE_DIR, $directoryPath);

        SimpleParameterProvider::setParameter(Option::CACHE_DIR, $directoryPath);
    }

    public function containerCacheDirectory(string $directoryPath): void
    {
        // container cache directory path must be a directory on the first place
        Assert::directory($directoryPath);

        $parameters = $this->parameters();
        $parameters->set(Option::CONTAINER_CACHE_DIRECTORY, $directoryPath);

        SimpleParameterProvider::setParameter(Option::CONTAINER_CACHE_DIRECTORY, $directoryPath);
    }

    /**
     * @param class-string<CacheStorageInterface> $cacheClass
     */
    public function cacheClass(string $cacheClass): void
    {
        Assert::isAOf($cacheClass, CacheStorageInterface::class);

        $parameters = $this->parameters();
        $parameters->set(Option::CACHE_CLASS, $cacheClass);

        SimpleParameterProvider::setParameter(Option::CACHE_CLASS, $cacheClass);
    }

    /**
     * @see https://github.com/nikic/PHP-Parser/issues/723#issuecomment-712401963
     */
    public function indent(string $character, int $count): void
    {
        $parameters = $this->parameters();

        $parameters->set(Option::INDENT_CHAR, $character);
        SimpleParameterProvider::setParameter(Option::INDENT_CHAR, $character);

        $parameters->set(Option::INDENT_SIZE, $count);
        SimpleParameterProvider::setParameter(Option::INDENT_SIZE, $count);
    }

    private function getServices(): ServicesConfigurator
    {
        if ($this->servicesConfigurator instanceof ServicesConfigurator) {
            return $this->servicesConfigurator;
        }

        $this->servicesConfigurator = $this->services();
        return $this->servicesConfigurator;
    }

    /**
     * @param class-string<RectorInterface|PhpRectorInterface|NonPhpRectorInterface> $rectorClass
     */
    private function tagRectorService(ServiceConfigurator $rectorServiceConfigurator, string $rectorClass): void
    {
        $rectorServiceConfigurator->tag(RectorInterface::class);

        if (is_a($rectorClass, PhpRectorInterface::class, true)) {
            $rectorServiceConfigurator->tag(PhpRectorInterface::class);
        } elseif (is_a($rectorClass, NonPhpRectorInterface::class, true)) {
            $rectorServiceConfigurator->tag(NonPhpRectorInterface::class);
        }
    }
}
