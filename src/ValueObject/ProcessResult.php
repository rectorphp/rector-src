<?php

declare(strict_types=1);

namespace Rector\ValueObject;

use Rector\Contract\Rector\RectorInterface;
use Rector\ValueObject\Error\SystemError;
use Rector\ValueObject\Reporting\FileDiff;
use Webmozart\Assert\Assert;

final class ProcessResult
{
    /**
     * @param SystemError[] $systemErrors
     * @param FileDiff[] $fileDiffs
     * @param array<string, string[]> $usedSkips
     * @param array<string, string[]> $skippedFilePathsByRule
     */
    public function __construct(
        private array $systemErrors,
        private readonly array $fileDiffs,
        private readonly int $totalChanged,
        private array $usedSkips = [],
        private readonly array $skippedFilePathsByRule = []
    ) {
        Assert::allIsInstanceOf($systemErrors, SystemError::class);
        Assert::allIsInstanceOf($fileDiffs, FileDiff::class);

        Assert::allString(array_keys($usedSkips));
        foreach ($usedSkips as $usedSkip) {
            Assert::allString($usedSkip);
        }

        Assert::allString(array_keys($skippedFilePathsByRule));
        foreach ($skippedFilePathsByRule as $skippedFilePaths) {
            Assert::allString($skippedFilePaths);
        }
    }

    /**
     * @return SystemError[]
     */
    public function getSystemErrors(): array
    {
        return $this->systemErrors;
    }

    /**
     * @return FileDiff[]
     */
    public function getFileDiffs(bool $onlyWithChanges = true): array
    {
        if ($onlyWithChanges) {
            return array_filter($this->fileDiffs, fn (FileDiff $fileDiff): bool => $fileDiff->getDiff() !== '');
        }

        return $this->fileDiffs;
    }

    /**
     * @param SystemError[] $systemErrors
     */
    public function addSystemErrors(array $systemErrors): void
    {
        Assert::allIsInstanceOf($systemErrors, SystemError::class);

        $this->systemErrors = [...$this->systemErrors, ...$systemErrors];
    }

    /**
     * Path-only skips are matched while finding files in the main process, but parallel runs build
     * their result from worker processes only. Merge those main-process marks back in, or they would
     * be wrongly reported as unused.
     *
     * @param array<string, string[]> $usedSkips
     */
    public function addUsedSkips(array $usedSkips): void
    {
        foreach ($usedSkips as $skip => $paths) {
            Assert::allString($paths);

            $existingPaths = $this->usedSkips[$skip] ?? [];
            $this->usedSkips[$skip] = array_values(array_unique([...$existingPaths, ...$paths]));
        }
    }

    public function getTotalChanged(): int
    {
        return $this->totalChanged;
    }

    /**
     * @return array<string, string[]>
     */
    public function getUsedSkips(): array
    {
        return $this->usedSkips;
    }

    /**
     * Real file paths skipped per rule-scoped path skip, to verify later whether the skip is needed
     *
     * @return array<string, string[]>
     */
    public function getSkippedFilePathsByRule(): array
    {
        return $this->skippedFilePathsByRule;
    }

    /**
     * @return array<class-string<RectorInterface>, int>
     */
    public function getRuleApplicationCounts(): array
    {
        $ruleCounts = [];

        foreach ($this->fileDiffs as $fileDiff) {
            foreach ($fileDiff->getRectorClasses() as $rectorClass) {
                $ruleCounts[$rectorClass] ??= 0;

                ++$ruleCounts[$rectorClass];
            }
        }

        arsort($ruleCounts);
        return $ruleCounts;
    }
}
