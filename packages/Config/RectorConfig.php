<?php

declare(strict_types=1);

namespace Rector\Config;

use Illuminate\Container\Container;
use Rector\Caching\Contract\ValueObject\Storage\CacheStorageInterface;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Contract\Rector\NonPhpRectorInterface;
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\ValueObject\PhpVersion;
//use Symfony\Component\DependencyInjection\Loader\Configurator\ServiceConfigurator;
//use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Webmozart\Assert\Assert;

/**
 * @api
 * Same as Symfony container configurator, with patched return type for "set()" method for easier DX.
 * It is an alias for internal class that is prefixed during build, so it's basically for keeping stable public API.
 */
final class RectorConfig extends Container
{
    //    private ?ServicesConfigurator $servicesConfigurator = null;

    /**
     * @param string[] $paths
     */
    public function paths(array $paths): void
    {
        Assert::allString($paths);

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
        SimpleParameterProvider::setParameter(Option::PARALLEL, false);
    }

    public function parallel(int $seconds = 120, int $maxNumberOfProcess = 16, int $jobSize = 20): void
    {
        SimpleParameterProvider::setParameter(Option::PARALLEL, true);
        SimpleParameterProvider::setParameter(Option::PARALLEL_JOB_TIMEOUT_IN_SECONDS, $seconds);
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
     * @param array<int|string, mixed> $criteria
     */
    public function skip(array $criteria): void
    {
        $notExistsRules = [];
        foreach ($criteria as $key => $value) {
            /**
             * Cover define rule then list of files
             *
             * $rectorConfig->skip([
             *      RenameVariableToMatchMethodCallReturnTypeRector::class => [
             *          __DIR__ . '/packages/Config/RectorConfig.php'
             *      ],
             * ]);
             */
            if ($this->isRuleNoLongerExists($key)) {
                $notExistsRules[] = $key;
            }

            if (! is_string($value)) {
                continue;
            }

            /**
             * Cover direct value without array list of files, eg:
             *
             * $rectorConfig->skip([
             *      StringClassNameToClassConstantRector::class,
             * ]);
             */
            if ($this->isRuleNoLongerExists($value)) {
                $notExistsRules[] = $value;
            }
        }

        if ($notExistsRules !== []) {
            throw new ShouldNotHappenException(
                'Following skipped rules on $rectorConfig->skip() are no longer exists or changed to different namespace: ' . implode(
                    ', ',
                    $notExistsRules
                )
            );
        }

        SimpleParameterProvider::addParameter(Option::SKIP, $criteria);
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
     * Set PHPStan custom config to load extensions and custom configuration to Rector.
     * By default, the "phpstan.neon" path is used.
     */
    public function phpstanConfig(string $filePath): void
    {
        Assert::fileExists($filePath);

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

        // decorate with value object inliner so Symfony understands, see https://getrector.com/blog/2020/09/07/how-to-inline-value-object-in-symfony-php-config
        //        array_walk_recursive($configuration, static function (&$value) {


        $this->singleton($rectorClass);
        $this->tagRectorService($rectorClass);

        $this->extend($rectorClass, function (ConfigurableRectorInterface $configurableRector) use ($configuration) {
            $configurableRector->configure([$configuration]);
        });
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    public function rule(string $rectorClass): void
    {
        Assert::classExists($rectorClass);
        Assert::isAOf($rectorClass, RectorInterface::class);

        $this->singleton($rectorClass);
        $this->tagRectorService($rectorClass);
    }

    /**
     * @param array<class-string<RectorInterface>> $rectorClasses
     */
    public function rules(array $rectorClasses): void
    {
        Assert::allString($rectorClasses);
        $this->ensureNotDuplicatedClasses($rectorClasses);

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

    /**
     * @deprecated Needed only for removed symfony di
     */
    public function containerCacheDirectory(string $directoryPath): void
    {
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

<<<<<<< HEAD
    private function isRuleNoLongerExists(mixed $skipRule): bool
    {
        return // only validate string
            is_string($skipRule)
            // not regex path
            && ! str_contains($skipRule, '*')
            // not realpath
            && realpath($skipRule) === false
            // a Rector end
            && str_ends_with($skipRule, 'Rector')
            // class not exists
            && ! class_exists($skipRule);
=======
    public function import(string $setFilePath): void
    {
        $self = $this;
        $closureFilePath = (require $setFilePath);

        \Webmozart\Assert\Assert::isCallable($closureFilePath);
        $closureFilePath($self);
>>>>>>> d6cb3cc836 (refactor RectorConfig to Laravel container)
    }

    /**
     * @param string[] $values
     * @return string[]
     */
    private function resolveDuplicatedValues(array $values): array
    {
        $counted = array_count_values($values);
        $duplicates = [];

        foreach ($counted as $value => $count) {
            if ($count > 1) {
                $duplicates[] = $value;
            }
        }

        return array_unique($duplicates);
    }

    /**
     * @param class-string<RectorInterface|PhpRectorInterface> $rectorClass
     */
    private function tagRectorService(string $rectorClass): void
    {
        $this->tag($rectorClass, RectorInterface::class);

        if (is_a($rectorClass, PhpRectorInterface::class, true)) {
            $this->tag($rectorClass, PhpRectorInterface::class);
        } elseif (is_a($rectorClass, NonPhpRectorInterface::class, true)) {
<<<<<<< HEAD
            trigger_error(sprintf(
                'The "%s" interface of "%s" rule is deprecated. Rector will only PHP code, as designed to with AST. For another file format, use custom tooling.',
                NonPhpRectorInterface::class,
                $rectorClass,
            ));
            exit();
=======
            $this->tag($rectorClass, NonPhpRectorInterface::class);
>>>>>>> ffecf2aecf (refactor RectorConfig to Laravel container)
        }
    }

    /**
     * @param string[] $rectorClasses
     */
    private function ensureNotDuplicatedClasses(array $rectorClasses): void
    {
        $duplicatedRectorClasses = $this->resolveDuplicatedValues($rectorClasses);
        if ($duplicatedRectorClasses === []) {
            return;
        }

        throw new ShouldNotHappenException('Following rules are registered twice: ' . implode(
            ', ',
            $duplicatedRectorClasses
        ));
    }
}
