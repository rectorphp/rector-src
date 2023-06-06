<?php

declare(strict_types=1);

namespace Rector\Core\ValueObjectFactory;

use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\ProcessResult;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\Parallel\ValueObject\Bridge;

final class ProcessResultFactory
{
    public function __construct(
        private readonly RemovedAndAddedFilesCollector $removedAndAddedFilesCollector,
    ) {
    }

    /**
     * @param array{system_errors: SystemError[], file_diffs: FileDiff[]} $errorsAndFileDiffs
     */
    public function create(array $errorsAndFileDiffs): ProcessResult
    {
        $systemErrors = $errorsAndFileDiffs[Bridge::SYSTEM_ERRORS];
        $fileDiffs = $errorsAndFileDiffs[Bridge::FILE_DIFFS];

        return new ProcessResult(
            $systemErrors,
            $fileDiffs,
            $this->removedAndAddedFilesCollector->getAddedFileCount(),
            $this->removedAndAddedFilesCollector->getRemovedFilesCount(),
        );
    }
}
