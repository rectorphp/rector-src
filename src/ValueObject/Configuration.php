<?php

declare(strict_types=1);

namespace Rector\ValueObject;

use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Contract\Rector\RectorInterface;
use Rector\ValueObject\Configuration\LevelOverflow;
use Webmozart\Assert\Assert;

final readonly class Configuration
{
    /**
     * @param string[] $fileExtensions
     * @param string[] $paths
     * @param LevelOverflow[] $levelOverflows
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
        private ?string $onlyRule = null,
        private ?string $onlySuffix = null,
        private array $levelOverflows = [],
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
     * @return class-string<RectorInterface>|null
     */
    public function getOnlyRule(): ?string
    {
        return $this->onlyRule;
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

    public function getOnlySuffix(): ?string
    {
        return $this->onlySuffix;
    }

    /**
     * @return LevelOverflow[]
     */
    public function getLevelOverflows(): array
    {
        return $this->levelOverflows;
    }

    /**
     * @return string[]
     */
    public function getBothSetAndRulesDuplicatedRegistrations(): array
    {
        $rootStandaloneRegisteredRules = SimpleParameterProvider::provideArrayParameter(
            Option::ROOT_STANDALONE_REGISTERED_RULES
        );
        $setRegisteredRules = SimpleParameterProvider::provideArrayParameter(Option::SET_REGISTERED_RULES);

        $ruleDuplicatedRegistrations = array_intersect($rootStandaloneRegisteredRules, $setRegisteredRules);

        return array_unique($ruleDuplicatedRegistrations);
    }
}
