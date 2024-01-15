<?php

declare(strict_types=1);

namespace Rector\ValueObject;

use Rector\ValueObject\Error\SystemError;
use Rector\ValueObject\Reporting\FileDiff;
use Webmozart\Assert\Assert;

final readonly class FileProcessResult
{
    /**
     * @param SystemError[] $systemErrors
     */
    public function __construct(
        private array $systemErrors,
        private ?FileDiff $fileDiff
    ) {
        Assert::allIsInstanceOf($systemErrors, SystemError::class);
    }

    /**
     * @return SystemError[]
     */
    public function getSystemErrors(): array
    {
        return $this->systemErrors;
    }

    public function getFileDiff(): ?FileDiff
    {
        return $this->fileDiff;
    }
}
