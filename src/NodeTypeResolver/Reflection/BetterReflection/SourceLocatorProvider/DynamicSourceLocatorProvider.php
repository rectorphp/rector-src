<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\Reflection\BetterReflection\SourceLocatorProvider;

use PHPStan\BetterReflection\Identifier\IdentifierType;
use PHPStan\BetterReflection\Reflector\DefaultReflector;
use PHPStan\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use PHPStan\BetterReflection\SourceLocator\Type\SourceLocator;
use PHPStan\Broker\ClassNotFoundException;
use PHPStan\File\CouldNotReadFileException;
use PHPStan\Reflection\BetterReflection\SourceLocator\FileNodesFetcher;
use PHPStan\Reflection\BetterReflection\SourceLocator\NewOptimizedDirectorySourceLocator;
use PHPStan\Reflection\BetterReflection\SourceLocator\OptimizedDirectorySourceLocatorFactory;
use PHPStan\Reflection\BetterReflection\SourceLocator\OptimizedSingleFileSourceLocator;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Caching\Cache;
use Rector\Caching\Enum\CacheKey;
use Rector\Contract\DependencyInjection\ResetableInterface;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;

/**
 * @api phpstan external
 */
final class DynamicSourceLocatorProvider implements ResetableInterface
{
    /**
     * @var string[]
     */
    private array $filePaths = [];

    /**
     * @var string[]
     */
    private array $directories = [];

    private ?AggregateSourceLocator $aggregateSourceLocator = null;

    private ReflectionProvider $reflectionProvider;
    private Cache $cache;

    /**
     * @var string
     */
    private const CLASSNAMES_CACHE = 'classname_cache';

    public function __construct(
        private readonly FileNodesFetcher $fileNodesFetcher,
        private readonly OptimizedDirectorySourceLocatorFactory $optimizedDirectorySourceLocatorFactory
    ) {
    }

    public function autowire(
        ReflectionProvider $reflectionProvider,
        Cache $cache,
    ): void
    {
        $this->reflectionProvider = $reflectionProvider;
        $this->cache = $cache;
    }

    public function setFilePath(string $filePath): void
    {
        $this->filePaths = [$filePath];
    }

    /**
     * @param string[] $files
     */
    public function addFiles(array $files): void
    {
        $this->filePaths = array_merge($this->filePaths, $files);
    }

    /**
     * @param string[] $directories
     */
    public function addDirectories(array $directories): void
    {
        $this->directories = array_merge($this->directories, $directories);
    }

    public function provide(): SourceLocator
    {
        // do not cache for PHPUnit, as in test every fixture is different
        $isPHPUnitRun = StaticPHPUnitEnvironment::isPHPUnitRun();

        if ($this->aggregateSourceLocator instanceof AggregateSourceLocator && ! $isPHPUnitRun) {
            return $this->aggregateSourceLocator;
        }

        $sourceLocators = [];

        foreach ($this->filePaths as $file) {
            $sourceLocators[] = new OptimizedSingleFileSourceLocator($this->fileNodesFetcher, $file);
        }

        foreach ($this->directories as $directory) {
            $sourceLocators[] = $this->optimizedDirectorySourceLocatorFactory->createByDirectory($directory);
        }

        $aggregateSourceLocator = $this->aggregateSourceLocator = new AggregateSourceLocator($sourceLocators);

        $this->collectClasses($aggregateSourceLocator, $sourceLocators);

        return $aggregateSourceLocator;
    }

    public function isPathsEmpty(): bool
    {
        return $this->filePaths === [] && $this->directories === [];
    }

    /**
     * @api to allow fast single-container tests
     */
    public function reset(): void
    {
        $this->filePaths = [];
        $this->directories = [];
        $this->aggregateSourceLocator = null;
    }

    /**
     * @param OptimizedSingleFileSourceLocator[]|NewOptimizedDirectorySourceLocator[] $sourceLocators
     */
    private function collectClasses(AggregateSourceLocator $aggregateSourceLocator, array $sourceLocators): void
    {
        if ($sourceLocators === []) {
            return;
        }

        // no need to collect classes on single file, will auto collected
        if (count($sourceLocators) === 1 && $sourceLocators[0] instanceof OptimizedSingleFileSourceLocator) {
            return;
        }

        $classNamesCache = $this->cache->load(self::CLASSNAMES_CACHE, CacheKey::PATHS_HASH_KEY);
        if ($classNamesCache !== []) {
            foreach ($classNamesCache as $classNameCache) {
                $this->reflectionProvider->getClass($classNameCache);
            }

            return;
        }

        $reflector = new DefaultReflector($aggregateSourceLocator);
        $identifierClass = new IdentifierType(IdentifierType::IDENTIFIER_CLASS);

        $classNames = [];
        foreach ($sourceLocators as $sourceLocator) {
            // trigger collect "classes" on get class on locate identifier
            try {
                $reflections = $sourceLocator->locateIdentifiersByType($reflector, $identifierClass);

                foreach ($reflections as $reflection) {
                    // make 'classes' collection
                    try {
                        $classNames[] = $this->reflectionProvider->getClass($reflection->getName());
                    } catch (ClassNotFoundException) {
                    }
                }
            } catch (CouldNotReadFileException) {
            }
        }

        $this->cache->save(self::CLASSNAMES_CACHE, CacheKey::PATHS_HASH_KEY, $classNames);
    }
}
