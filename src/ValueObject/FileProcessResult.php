<?php

declare(strict_types=1);

namespace Rector\Core\ValueObject;

use PHPStan\Collectors\CollectedData;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Webmozart\Assert\Assert;

final class FileProcessResult
{
    /**
     * @param SystemError[] $systemErrors
     * @param CollectedData[] $collectedDatas
     */
    public function __construct(
        private readonly array $systemErrors,
        private readonly ?FileDiff $fileDiff,
        private readonly array $collectedDatas
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
    public function getCollectedDatas(): array
    {
        return $this->collectedDatas;
    }
}
