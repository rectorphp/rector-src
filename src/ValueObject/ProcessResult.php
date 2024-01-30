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
    public function getFileDiffs(): array
    {
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
}
