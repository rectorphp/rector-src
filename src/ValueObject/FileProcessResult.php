<?php

declare(strict_types=1);

namespace Rector\ValueObject;

use PHPStan\Collectors\CollectedData;
use Rector\ValueObject\Error\SystemError;
use Rector\ValueObject\Reporting\FileDiff;
use Webmozart\Assert\Assert;

final readonly class FileProcessResult
{
    /**
     * @param SystemError[] $systemErrors
     * @param CollectedData[] $collectedDatas
     */
    public function __construct(
        private array $systemErrors,
        private ?FileDiff $fileDiff,
        private array $collectedDatas
    ) {
        Assert::allIsInstanceOf($systemErrors, SystemError::class);
        Assert::allIsInstanceOf($collectedDatas, CollectedData::class);
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

    /**
     * @return CollectedData[]
     */
    public function getCollectedData(): array
    {
        return $this->collectedDatas;
    }
}
