<?php

declare(strict_types=1);

namespace Rector\ValueObject;

use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Webmozart\Assert\Assert;

final readonly class Configuration
{
    /**
     * @param string[] $fileExtensions
     * @param string[] $paths
     */
    public function __construct(
        private bool $isDryRun = false,
        private bool $showProgressBar = true,
        private bool $shouldClearCache = false,
        private string $outputFormat = ConsoleOutputFormatter::NAME,
        private array $fileExtensions = ['php'],
        private array $paths = [],
        private bool $showDiffs = true,
        private string | null $parallelPort = null,
        private string | null $parallelIdentifier = null,
        private bool $isParallel = false,
        private string|null $memoryLimit = null,
        private bool $isDebug = false,
        private bool $reportingWithRealPath = false,
        private int $batchIndex = 0,
        private int $batchTotal = 0,
    ) {
    }

    public function isDryRun(): bool
    {
        return $this->isDryRun;
    }

    public function shouldShowProgressBar(): bool
    {
        return $this->showProgressBar;
    }

    public function shouldClearCache(): bool
    {
        return $this->shouldClearCache;
    }

    /**
     * @return string[]
     */
    public function getFileExtensions(): array
    {
        Assert::notEmpty($this->fileExtensions);
        return $this->fileExtensions;
    }

    /**
     * @return string[]
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    public function getOutputFormat(): string
    {
        return $this->outputFormat;
    }

    public function shouldShowDiffs(): bool
    {
        return $this->showDiffs;
    }

    public function getParallelPort(): ?string
    {
        return $this->parallelPort;
    }

    public function getParallelIdentifier(): ?string
    {
        return $this->parallelIdentifier;
    }

    public function isParallel(): bool
    {
        return $this->isParallel;
    }

    public function getMemoryLimit(): ?string
    {
        return $this->memoryLimit;
    }

    public function isDebug(): bool
    {
        return $this->isDebug;
    }

    public function isReportingWithRealPath(): bool
    {
        return $this->reportingWithRealPath;
    }

    public function getBatchIndex(): int
    {
        return $this->batchIndex;
    }

    public function getBatchTotal(): int
    {
        return $this->batchTotal;
    }
}
