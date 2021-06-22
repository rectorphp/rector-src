<?php

declare(strict_types=1);

namespace Rector\Core\ValueObject;

use JetBrains\PhpStorm\Immutable;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;

#[Immutable]
final class Configuration
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
}
