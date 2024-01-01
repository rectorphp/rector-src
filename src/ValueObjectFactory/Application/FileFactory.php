<?php

declare(strict_types=1);

namespace Rector\Core\ValueObjectFactory\Application;

use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Core\ValueObject\Configuration;
use Rector\FileSystem\FilesFinder;

/**
 * @see \Rector\Core\ValueObject\Application\File
 */
final class FileFactory
{
    public function __construct(
        private readonly FilesFinder $filesFinder,
        private readonly ChangedFilesDetector $changedFilesDetector,
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
        $filePaths = $this->filesFinder->findInDirectoriesAndFiles($paths, $supportedFileExtensions);

        $fileWithExtensionsFilter = static function (string $filePath) use ($supportedFileExtensions): bool {
            $filePathExtension = pathinfo($filePath, PATHINFO_EXTENSION);
            return in_array($filePathExtension, $supportedFileExtensions, true);
        };

        return array_filter($filePaths, $fileWithExtensionsFilter);
    }
}
