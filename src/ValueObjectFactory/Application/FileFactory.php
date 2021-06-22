<?php

declare(strict_types=1);

namespace Rector\Core\ValueObjectFactory\Application;

use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Core\Configuration\Configuration;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\FileSystem\FilesFinder;
use Rector\Core\ValueObject\Application\File;

/**
 * @see \Rector\Core\ValueObject\Application\File
 */
final class FileFactory
{
    /**
     * @param FileProcessorInterface[] $fileProcessors
     */
    public function __construct(
        private FilesFinder $filesFinder,
        private Configuration $configuration,
        private ChangedFilesDetector $changedFilesDetector,
        private array $fileProcessors
    ) {
    }

    /**
     * @param string[] $paths
     * @return File[]
     */
    public function createFromPaths(array $paths): array
    {
        if ($this->configuration->shouldClearCache()) {
            $this->changedFilesDetector->clear();
        }

        $supportedFileExtensions = $this->resolveSupportedFileExtensions();
        $fileInfos = $this->filesFinder->findInDirectoriesAndFiles($paths, $supportedFileExtensions);

        $files = [];
        foreach ($fileInfos as $fileInfo) {
            $files[] = new File($fileInfo, $fileInfo->getContents());
        }

        return $files;
    }

    /**
     * @return string[]
     */
    private function resolveSupportedFileExtensions(): array
    {
        $supportedFileExtensions = [];

        foreach ($this->fileProcessors as $fileProcessor) {
            $supportedFileExtensions = array_merge(
                $supportedFileExtensions,
                $fileProcessor->getSupportedFileExtensions()
            );
        }

        return array_unique($supportedFileExtensions);
    }
}
