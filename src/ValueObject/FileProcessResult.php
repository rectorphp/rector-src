<?php

declare(strict_types=1);

namespace Rector\Core\ValueObject;

use PHPStan\Collectors\CollectedData;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\Reporting\FileDiff;

final class FileProcessResult
{
    /**
     * @param SystemError[] $systemErrors
     * @param CollectedData[] $collectedData
     */
    public function __construct(
        private readonly array $systemErrors,
        private readonly ?FileDiff $fileDiff,
        private readonly array $collectedData
    ) {
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
        return $this->collectedData;
    }
}
