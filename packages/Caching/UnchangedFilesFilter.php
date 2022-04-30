<?php

declare(strict_types=1);

namespace Rector\Caching;

use Rector\Caching\Detector\ChangedFilesDetector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class UnchangedFilesFilter
{
    public function __construct(
        private readonly ChangedFilesDetector $changedFilesDetector
    ) {
    }

    /**
     * @param SmartFileInfo[]|string[] $fileInfosOrPaths
     * @return SmartFileInfo[]
     */
    public function filterAndJoinWithDependentFileInfos(array $fileInfosOrPaths): array
    {
        $changedFileInfos = [];
        $dependentFileInfos = [];

        foreach ($fileInfosOrPaths as $fileInfo) {
            $fileInfo = is_string($fileInfo)
                ? new SmartFileInfo($fileInfo)
                : $fileInfo;

            if (! $this->changedFilesDetector->hasFileChanged($fileInfo)) {
                continue;
            }

            $changedFileInfos[] = $fileInfo;
            $this->changedFilesDetector->invalidateFile($fileInfo);

            $dependentFileInfos = array_merge(
                $dependentFileInfos,
                $this->changedFilesDetector->getDependentFileInfos($fileInfo)
            );
        }

        // add dependent files
        $dependentFileInfos = array_merge($dependentFileInfos, $changedFileInfos);

        return array_unique($dependentFileInfos);
    }
}
