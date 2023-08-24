<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\Reflection\BetterReflection\SourceLocatorProvider;

use PHPStan\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use PHPStan\BetterReflection\SourceLocator\Type\SourceLocator;
use PHPStan\Reflection\BetterReflection\SourceLocator\FileNodesFetcher;
use PHPStan\Reflection\BetterReflection\SourceLocator\OptimizedSingleFileSourceLocator;
use Rector\Core\Contract\DependencyInjection\ResetableInterface;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Webmozart\Assert\Assert;
use PHPStan\Reflection\BetterReflection\SourceLocator\OptimizedDirectorySourceLocatorFactory;

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
     * @var array<string, string[]>
     */
    private array $filesByDirectory = [];

    private ?AggregateSourceLocator $aggregateSourceLocator = null;

    public function __construct(
        private readonly FileNodesFetcher $fileNodesFetcher,
        private readonly OptimizedDirectorySourceLocatorFactory $optimizedDirectorySourceLocatorFactory
    ) {
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

        foreach ($this->filesByDirectory as $directory => $files) {
            $sourceLocators[] = $this->optimizedDirectorySourceLocatorFactory->createByDirectory($directory);
        }

        $this->aggregateSourceLocator = new AggregateSourceLocator($sourceLocators);

        return $this->aggregateSourceLocator;
    }

    /**
     * @param string[] $files
     */
    public function addFilesByDirectory(string $directory, array $files): void
    {
        Assert::allString($files);

        $this->filesByDirectory[$directory] = $files;
    }

    public function isPathsEmpty(): bool
    {
        return $this->filePaths === [] && $this->filesByDirectory === [];
    }

    /**
     * @api to allow fast single-container tests
     */
    public function reset(): void
    {
        $this->filePaths = [];
        $this->filesByDirectory = [];
        $this->aggregateSourceLocator = null;
    }
}
