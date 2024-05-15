<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\Reflection\BetterReflection\SourceLocatorProvider;

use PHPStan\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use PHPStan\BetterReflection\SourceLocator\Type\SourceLocator;
use PHPStan\Reflection\BetterReflection\SourceLocator\FileNodesFetcher;
use PHPStan\Reflection\BetterReflection\SourceLocator\OptimizedDirectorySourceLocatorFactory;
use PHPStan\Reflection\BetterReflection\SourceLocator\OptimizedSingleFileSourceLocator;
use Rector\Caching\Enum\CacheKey;
use Rector\Contract\DependencyInjection\ResetableInterface;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Rector\Util\FileHasher;

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

    private FileHasher $fileHasher;

    public function __construct(
        private readonly FileNodesFetcher $fileNodesFetcher,
        private readonly OptimizedDirectorySourceLocatorFactory $optimizedDirectorySourceLocatorFactory
    ) {
    }

    public function autowire(FileHasher $fileHasher): void
    {
        $this->fileHasher = $fileHasher;
    }

    public function setFilePath(string $filePath): void
    {
        $this->filePaths = [$filePath];
    }

    public function getCacheClassNameKey(): string
    {
        $paths = [];

        foreach ($this->filePaths as $filePath) {
            $paths[] = (string) realpath($filePath);
        }

        foreach ($this->directories as $directory) {
            $paths[] = (string) realpath($directory);
        }

        $paths = array_filter($paths);
        return CacheKey::CLASSNAMES_HASH_KEY . '_' . $this->fileHasher->hash((string) json_encode($paths));
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

    /**
     * @return AggregateSourceLocator
     */
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
}
