<?php

declare(strict_types=1);

namespace Rector\Parallel\Application;

use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Core\Application\FileProcessor;
use Rector\Core\ValueObject\Configuration;
use Symplify\Skipper\Skipper\Skipper;
use Symplify\SmartFileSystem\SmartFileInfo;

final class SingleFileProcessor
{
    public function __construct(
        private Skipper $skipper,
        private ChangedFilesDetector $changedFilesDetector,
        private FileProcessor $fileProcessor
    ) {
    }

    /**
     * @return array<string, array<FileDiff|CodingStandardError>>
     */
    public function processFileInfo(SmartFileInfo $smartFileInfo, Configuration $configuration): array
    {
        if ($this->skipper->shouldSkipFileInfo($smartFileInfo)) {
            return [];
        }

        $errorsAndDiffs = [];

        $this->changedFilesDetector->addFileWithDependencies($smartFileInfo, []);
        $errorsAndDiffs = $this->fileProcessor->refactor($smartFileInfo, $configuration);

        // invalidate broken file, to analyse in next run too
        if ($errorsAndDiffs !== []) {
            $this->changedFilesDetector->invalidateFileInfo($smartFileInfo);
        }

        return $errorsAndDiffs;
    }
}
