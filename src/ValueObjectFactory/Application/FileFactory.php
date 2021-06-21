<?php

declare(strict_types=1);

namespace Rector\Core\ValueObjectFactory\Application;

<<<<<<< HEAD
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Core\Configuration\Configuration;
use Rector\Core\Contract\Processor\FileProcessorInterface;
=======
>>>>>>> 926e95019 (cleanup)
use Rector\Core\FileSystem\FilesFinder;
use Rector\Core\ValueObject\Application\File;

/**
 * @see \Rector\Core\ValueObject\Application\File
 */
final class FileFactory
{
    public function __construct(
<<<<<<< HEAD
        private FilesFinder $filesFinder,
        private Configuration $configuration,
        private ChangedFilesDetector $changedFilesDetector,
        private array $fileProcessors
=======
        private FilesFinder $filesFinder
>>>>>>> 926e95019 (cleanup)
    ) {
    }

    /**
     * @param string[] $paths
     * @return File[]
     */
    public function createFromPaths(array $paths): array
    {
<<<<<<< HEAD
        if ($this->configuration->shouldClearCache()) {
            $this->changedFilesDetector->clear();
        }

        $supportedFileExtensions = $this->resolveSupportedFileExtensions();
        $fileInfos = $this->filesFinder->findInDirectoriesAndFiles($paths, $supportedFileExtensions);
=======
        $fileInfos = $this->filesFinder->findInDirectoriesAndFiles($paths);
>>>>>>> 926e95019 (cleanup)

        $files = [];
        foreach ($fileInfos as $fileInfo) {
            $files[] = new File($fileInfo, $fileInfo->getContents());
        }

        return $files;
    }
}
