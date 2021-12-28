<?php

declare(strict_types=1);

namespace Rector\Core\ValueObjectFactory\Application;

use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\FileSystem\FilesFinder;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @see \Rector\Core\ValueObject\Application\File
 */
final class FileFactory
{
    /**
     * @param FileProcessorInterface[] $fileProcessors
     */
    public function __construct(
        private readonly FilesFinder $filesFinder,
        private readonly ChangedFilesDetector $changedFilesDetector,
        private readonly array $fileProcessors,
    ) {
    }

    /**
     * @param string[] $paths
     * @return SmartFileInfo[]
     */
    public function createFileInfosFromPaths(array $paths, Configuration $configuration): array
    {
        if ($configuration->shouldClearCache()) {
            $this->changedFilesDetector->clear();
        }

        $supportedFileExtensions = $this->resolveSupportedFileExtensions($configuration);

        return $this->filesFinder->findInDirectoriesAndFiles($paths, $supportedFileExtensions);
    }

    /**
     * @param string[] $paths
     * @return File[]
     */
    public function createFromPaths(array $paths, Configuration $configuration): array
    {
        $fileInfos = $this->createFileInfosFromPaths($paths, $configuration);

        $files = [];
        foreach ($fileInfos as $fileInfo) {
            $files[] = new File($fileInfo, $fileInfo->getContents());
        }

        return $files;
    }

    /**
     * @return string[]
     */
    private function resolveSupportedFileExtensions(Configuration $configuration): array
    {
        $supportedFileExtensions = [];

        foreach ($this->fileProcessors as $fileProcessor) {
            $supportedFileExtensions = array_merge(
                $supportedFileExtensions,
                $fileProcessor->getSupportedFileExtensions()
            );
        }

        // basic PHP extensions
        $supportedFileExtensions = array_merge($supportedFileExtensions, $configuration->getFileExtensions());

        return array_unique($supportedFileExtensions);
    }
}
