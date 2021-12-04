<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\Reflection\BetterReflection\SourceLocatorProvider;

use PHPStan\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use PHPStan\BetterReflection\SourceLocator\Type\SourceLocator;
use PHPStan\Php\PhpVersion;
use PHPStan\Reflection\BetterReflection\SourceLocator\FileNodesFetcher;
use PHPStan\Reflection\BetterReflection\SourceLocator\OptimizedDirectorySourceLocator;
use PHPStan\Reflection\BetterReflection\SourceLocator\OptimizedSingleFileSourceLocator;
use Rector\NodeTypeResolver\Contract\SourceLocatorProviderInterface;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Symplify\SmartFileSystem\SmartFileInfo;

final class DynamicSourceLocatorProvider implements SourceLocatorProviderInterface
{
    /**
     * @var string[]
     */
    private array $files = [];

    /**
     * @var array<string, string[]>
     */
    private array $filesByDirectory = [];

    private ?AggregateSourceLocator $aggregateSourceLocator = null;

    public function __construct(
        private readonly FileNodesFetcher $fileNodesFetcher,
        private readonly PhpVersion $phpVersion
    ) {
    }

    public function setFileInfo(SmartFileInfo $fileInfo): void
    {
        $this->files = [$fileInfo->getRealPath()];
    }

    /**
     * @param string[] $files
     */
    public function addFiles(array $files): void
    {
        $this->files = array_merge($this->files, $files);
    }

    public function provide(): SourceLocator
    {
        // do not cache for PHPUnit, as in test every fixture is different
        $isPHPUnitRun = StaticPHPUnitEnvironment::isPHPUnitRun();

        if ($this->aggregateSourceLocator instanceof AggregateSourceLocator && ! $isPHPUnitRun) {
            return $this->aggregateSourceLocator;
        }

        $sourceLocators = [];
        foreach ($this->files as $file) {
            $sourceLocators[] = new OptimizedSingleFileSourceLocator($this->fileNodesFetcher, $file);
        }

        foreach ($this->filesByDirectory as $files) {
            $sourceLocators[] = new OptimizedDirectorySourceLocator($this->fileNodesFetcher, $this->phpVersion, $files);
        }

        $this->aggregateSourceLocator = new AggregateSourceLocator($sourceLocators);

        return $this->aggregateSourceLocator;
    }

    /**
     * @param string[] $files
     */
    public function addFilesByDirectory(string $directory, array $files): void
    {
        $this->filesByDirectory[$directory] = $files;
    }
}
