<?php

declare(strict_types=1);

namespace Rector\ValueObjectFactory\Application;

use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\FileSystem\FilesFinder;
use Rector\ValueObject\Configuration;

/**
 * @see \Rector\ValueObject\Application\File
 */
final readonly class FileFactory
{
    public function __construct(
        private FilesFinder $filesFinder,
        private ChangedFilesDetector $changedFilesDetector,
    ) {
    }

    /**
     * @param string[] $paths
     * @return string[]
     */
    public function findFilesInPaths(array $paths, Configuration $configuration): array
    {
        if ($configuration->shouldClearCache()) {
            $this->changedFilesDetector->clear();
        }

        $supportedFileExtensions = $configuration->getFileExtensions();
        return $this->filesFinder->findInDirectoriesAndFiles($paths, $supportedFileExtensions);
    }
}
