<?php

declare(strict_types=1);

namespace Rector\Parallel\ValueObject;

use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\Reporting\FileDiff;

final class ProcessFileResult
{
    /**
     * @param array{system_errors: SystemError[], file_diffs: FileDiff[]} $errorAndFileDiffs
     */
    public function __construct(
        private readonly bool $isFileChanged,
        private readonly array $errorAndFileDiffs
    ) {
    }

    public function isFileChanged(): bool
    {
        return $this->isFileChanged;
    }

    /**
     * @return array{system_errors: SystemError[], file_diffs: FileDiff[]}
     */
    public function getErrorAndFileDiffs(): array
    {
        return $this->errorAndFileDiffs;
    }
}
