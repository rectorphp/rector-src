<?php

declare(strict_types=1);

namespace Rector\Core\FileSystem;

use Rector\Caching\UnchangedFilesFilter;
use Rector\Core\Util\StringUtils;
use Rector\Core\ValueObject\StaticNonPhpFileSuffixes;
use Symplify\SmartFileSystem\SmartFileInfo;

final class PhpFilesFinder
{
    public function __construct(
        private readonly FilesFinder $filesFinder,
        private readonly UnchangedFilesFilter $unchangedFilesFilter
    ) {
    }

    /**
     * @param string[] $paths
     * @return SmartFileInfo[]
     */
    public function findInPaths(array $paths): array
    {
        $phpFileInfos = $this->filesFinder->findInDirectoriesAndFiles($paths);

        // filter out non-PHP files
        foreach ($phpFileInfos as $key => $phpFileInfo) {
            $pathName = $phpFileInfo->getPathname();
            if (StringUtils::isMatch($pathName, StaticNonPhpFileSuffixes::getSuffixRegexPattern())) {
                unset($phpFileInfos[$key]);
            }
        }

        return $this->unchangedFilesFilter->filterAndJoinWithDependentFileInfos($phpFileInfos);
    }
}
