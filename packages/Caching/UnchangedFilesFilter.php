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
     * @param string[] $filePaths
     * @return string[]
     */
    public function filterAndJoinWithDependentFileInfos(array $filePaths): array
    {
        $changedFileInfos = [];
        $dependentFileInfos = [];

        foreach ($filePaths as $filePath) {
            //            if (is_string($filePath)) {
            //                $filePath = new SmartFileInfo($filePath);
            //            }

            if (! $this->changedFilesDetector->hasFileChanged($filePath)) {
                continue;
            }

            $changedFileInfos[] = $filePath;
            $this->changedFilesDetector->invalidateFile($filePath);

            $dependentFileInfos = array_merge(
                $dependentFileInfos,
                $this->changedFilesDetector->getDependentFilePaths($filePath)
            );
        }

        // add dependent files
        $dependentFileInfos = array_merge($dependentFileInfos, $changedFileInfos);

        return array_unique($dependentFileInfos);
    }
}
