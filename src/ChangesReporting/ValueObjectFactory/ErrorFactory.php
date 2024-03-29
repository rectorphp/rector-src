<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\ValueObjectFactory;

use PHPStan\AnalysedCodeException;
use Rector\Error\ExceptionCorrector;
use Rector\FileSystem\FilePathHelper;
use Rector\ValueObject\Error\SystemError;

final readonly class ErrorFactory
{
    public function __construct(
        private ExceptionCorrector $exceptionCorrector,
        private FilePathHelper $filePathHelper
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
