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

    public function __construct(
        private readonly FileNodesFetcher $fileNodesFetcher,
        private readonly OptimizedDirectorySourceLocatorFactory $optimizedDirectorySourceLocatorFactory
    ) {
    }

    public function setReflectionProvider(ReflectionProvider $reflectionProvider): void
    {
        $this->reflectionProvider = $reflectionProvider;
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

        $this->collectClasses($sourceLocators, $isPHPUnitRun);

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
    private function collectClasses(array $sourceLocators, bool $isPHPUnitRun): void
    {
        if ($sourceLocators === []) {
            return;
        }

        if (! $this->aggregateSourceLocator instanceof AggregateSourceLocator) {
            return;
        }

        // in PHPUnit Rector fixture, parent and child for test needs in same file
        // no need to collect classes
        if ($isPHPUnitRun) {
            return;
        }

        // use AggregateSourceLocator from property fetch, otherwise, it will cause infinite loop
        $reflector = new DefaultReflector($this->aggregateSourceLocator);

        foreach ($sourceLocators as $sourceLocator) {
            // trigger collect "classes" on get class on locate identifier
            try {
                $reflections = $sourceLocator->locateIdentifiersByType(
                    $reflector,
                    new class() extends IdentifierType {
                        public function isClass(): bool
                        {
                            return true;
                        }
                    }
                );

                foreach ($reflections as $reflection) {
                    // make 'classes' collection
                    try {
                        $this->reflectionProvider->getClass($reflection->getName());
                    } catch (ClassNotFoundException) {
                    }
                }
            } catch (CouldNotReadFileException) {
            }
        }
    }
}
