<?php

declare(strict_types=1);

namespace Rector\ValueObject;

use Rector\ValueObject\Error\SystemError;
use Rector\ValueObject\Reporting\FileDiff;
use Webmozart\Assert\Assert;

final class ProcessResult
{
    /**
     * @param SystemError[] $systemErrors
     * @param FileDiff[] $fileDiffs
     */
    public function __construct(
        private array $systemErrors,
        private readonly array $fileDiffs,
        private readonly int $totalChanged
    ) {
        Assert::allIsInstanceOf($systemErrors, SystemError::class);
        Assert::allIsInstanceOf($fileDiffs, FileDiff::class);
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

    public function hasChanged(): bool
    {
        return $this->totalChanged > 0;
    }

    public function getTotalChanged(): int
    {
        return $this->totalChanged;
    }
}
