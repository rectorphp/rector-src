<?php

declare(strict_types=1);

namespace Rector\Core\Application\FileProcessor;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Reporting\FileDiff;

final class ChainFileProcessor
{
    /**
     * @var string[]
     */
    private readonly array $supportedFileExtensions;

    /**
     * @param FileProcessorInterface[] $fileProcessors
     */
    public function __construct(
        private readonly array $fileProcessors = [],
    ) {
        $supportedFileExtensions = [];

        foreach ($this->fileProcessors as $fileProcessor) {
            $supportedFileExtensions = array_merge(
                $supportedFileExtensions,
                $fileProcessor->getSupportedFileExtensions(),
            );
        }

        $this->supportedFileExtensions = $supportedFileExtensions;
    }

    public function process(File $file, Configuration $configuration): ?FileDiff
    {
        foreach ($this->fileProcessors as $fileProcessor) {
            if (! $fileProcessor->supports($file, $configuration)) {
                continue;
            }

            $fileDiff = $fileProcessor->process($file, $configuration);

            if ($fileDiff instanceof FileDiff) {
                return $fileDiff;
            }
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function getSupportedFileExtensions(): array
    {
        return $this->supportedFileExtensions;
    }
}
