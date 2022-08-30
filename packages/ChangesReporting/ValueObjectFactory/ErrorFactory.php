<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\ValueObjectFactory;

use PHPStan\AnalysedCodeException;
use Rector\Core\Error\ExceptionCorrector;
use Rector\Core\FileSystem\FilePathHelper;
use Rector\Core\ValueObject\Error\SystemError;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ErrorFactory
{
    public function __construct(
        private readonly ExceptionCorrector $exceptionCorrector,
        private readonly FilePathHelper $filePathHelper
    ) {
    }

    public function createAutoloadError(
        AnalysedCodeException $analysedCodeException,
        SmartFileInfo $smartFileInfo
    ): SystemError {
        $message = $this->exceptionCorrector->getAutoloadExceptionMessageAndAddLocation($analysedCodeException);
        $relativeFilePath = $this->filePathHelper->relativePath($smartFileInfo->getRealPath());

        return new SystemError($message, $relativeFilePath);
    }
}
