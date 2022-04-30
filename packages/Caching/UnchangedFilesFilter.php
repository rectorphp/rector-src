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
     * @param SmartFileInfo[]|string[] $fileInfos
     * @return SmartFileInfo[]
     */
    public function filterAndJoinWithDependentFileInfos(array $fileInfos): array
    {
        $changedFileInfos = [];
        $dependentFileInfos = [];

        foreach ($fileInfos as $fileInfo) {
            if (is_string($fileInfo)) {
                $fileInfo = new SmartFileInfo($fileInfo);
            }

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
