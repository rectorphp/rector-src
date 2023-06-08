<?php

declare(strict_types=1);

namespace Rector\Core\ValueObject;

use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Core\ValueObjectFactory\ProcessResultFactory
 */
final class ProcessResult
{
    /**
     * @param FileDiff[] $fileDiffs
     * @param SystemError[] $systemErrors
     */
    public function __construct(
        private readonly array $systemErrors,
        private readonly array $fileDiffs,
    ) {
        Assert::allIsAOf($fileDiffs, FileDiff::class);
        Assert::allIsAOf($systemErrors, SystemError::class);
    }

    /**
     * @return FileDiff[]
     */
    public function getFileDiffs(): array
    {
        return $this->fileDiffs;
    }

    /**
     * @return SystemError[]
     */
    public function getErrors(): array
    {
        return $this->systemErrors;
    }
}
