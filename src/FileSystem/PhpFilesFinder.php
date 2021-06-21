<?php

declare(strict_types=1);

namespace Rector\Core\FileSystem;

<<<<<<< HEAD
use Rector\Caching\UnchangedFilesFilter;
use Rector\Core\Configuration\Configuration;
=======
use Rector\Caching\Application\CachedFileInfoFilterAndReporter;
<<<<<<< HEAD
>>>>>>> dafa1d46c (unprefix options, already in class name)
=======
use Rector\Core\ValueObject\Configuration;
>>>>>>> 926e95019 (cleanup)
use Symplify\SmartFileSystem\SmartFileInfo;

final class PhpFilesFinder
{
    public function __construct(
        private FilesFinder $filesFinder,
<<<<<<< HEAD
        private Configuration $configuration,
        private UnchangedFilesFilter $unchangedFilesFilter,
=======
        private CachedFileInfoFilterAndReporter $cachedFileInfoFilterAndReporter
>>>>>>> dafa1d46c (unprefix options, already in class name)
    ) {
    }

    /**
     * @param string[] $paths
     * @return SmartFileInfo[]
     */
    public function findInPaths(array $paths, Configuration $configuration): array
    {
        $phpFileInfos = $this->filesFinder->findInDirectoriesAndFiles($paths, $configuration->getFileExtensions());

        // filter out non-PHP php files, e.g. blade templates in Laravel
        $phpFileInfos = array_filter(
            $phpFileInfos,
            fn (SmartFileInfo $smartFileInfo): bool => ! \str_ends_with($smartFileInfo->getPathname(), '.blade.php')
        );

<<<<<<< HEAD
        return $this->unchangedFilesFilter->filterAndJoinWithDependentFileInfos($phpFileInfos);
=======
        return $this->cachedFileInfoFilterAndReporter->filterFileInfos($phpFileInfos, $configuration);
>>>>>>> 926e95019 (cleanup)
    }
}
