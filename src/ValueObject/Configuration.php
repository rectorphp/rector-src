<?php

declare(strict_types=1);

namespace Rector\Core\ValueObject;

use PHPStan\Collectors\CollectedData;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Webmozart\Assert\Assert;

final class Configuration
{
    private bool $isSecondRun = false;

    /**
     * @var CollectedData[]
     */
    private array $collectedData = [];

    /**
     * @var CollectedData[]
     */
    private array $collectedDatas = [];

    /**
     * @param string[] $fileExtensions
     * @param string[] $paths
     */
    public function __construct(
        private readonly bool $isDryRun = false,
        private readonly bool $showProgressBar = true,
        private readonly bool $shouldClearCache = false,
        private readonly string $outputFormat = ConsoleOutputFormatter::NAME,
        private readonly array $fileExtensions = ['php'],
        private readonly array $paths = [],
        private readonly bool $showDiffs = true,
        private readonly string | null $parallelPort = null,
        private readonly string | null $parallelIdentifier = null,
        private readonly bool $isParallel = false,
        private readonly string|null $memoryLimit = null,
        private readonly bool $isDebug = false
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

    /**
<<<<<<< HEAD
     * @api
=======
>>>>>>> 44a91d291b (Add collector processor)
     * @param CollectedData[] $collectedDatas
     */
    public function setCollectedDatas(array $collectedDatas): void
    {
<<<<<<< HEAD
        $this->collectedData = $collectedDatas;
    }

    /**
     * @api
=======
        $this->collectedDatas = $collectedDatas;
    }

    /**
>>>>>>> 44a91d291b (Add collector processor)
     * @return CollectedData[]
     */
    public function getCollectedDatas(): array
    {
<<<<<<< HEAD
        return $this->collectedData;
    }

    /**
     * @api
     */
    public function enableSecondRun(): void
    {
        $this->isSecondRun = true;
    }

    /**
     * @api
     */
    public function isSecondRun(): bool
    {
        return $this->isSecondRun;
=======
        return $this->collectedDatas;
>>>>>>> 44a91d291b (Add collector processor)
    }
}
