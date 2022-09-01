<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\ValueObjectFactory;

use PHPStan\AnalysedCodeException;
use Rector\Core\Error\ExceptionCorrector;
use Rector\Core\FileSystem\FilePathHelper;
use Rector\Core\ValueObject\Error\SystemError;

final class ErrorFactory
{
    public function __construct(
        private readonly ExceptionCorrector $exceptionCorrector,
        private readonly FilePathHelper $filePathHelper
    ) {
    }

    public function createAutoloadError(
        AnalysedCodeException $analysedCodeException,
        string $filePath
    ): SystemError {
        $message = $this->exceptionCorrector->getAutoloadExceptionMessageAndAddLocation($analysedCodeException);
        $relativeFilePath = $this->filePathHelper->relativePath($filePath);

        return new SystemError($message, $relativeFilePath);
    }
}
