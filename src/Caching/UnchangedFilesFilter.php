<?php

declare(strict_types=1);

namespace Rector\Caching;

use Rector\Caching\Detector\ChangedFilesDetector;

final readonly class UnchangedFilesFilter
{
    public function __construct(
        private ChangedFilesDetector $changedFilesDetector
    ) {
    }

    /**
     * @param string[] $filePaths
     * @return string[]
     */
    public function filterFilePaths(array $filePaths): array
    {
        $changedFileInfos = [];
        $filePaths = array_unique($filePaths);

        foreach ($filePaths as $filePath) {
            if (! $this->changedFilesDetector->hasFileChanged($filePath)) {
                continue;
            }

            $changedFileInfos[] = $filePath;
            $this->changedFilesDetector->invalidateFile($filePath);
        }

        return $changedFileInfos;
    }
}
