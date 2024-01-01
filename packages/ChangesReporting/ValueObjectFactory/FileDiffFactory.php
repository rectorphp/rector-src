<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\ValueObjectFactory;

use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Console\Formatter\ConsoleDiffer;
use Rector\Differ\DefaultDiffer;
use Rector\FileSystem\FilePathHelper;
use Rector\ValueObject\Application\File;
use Rector\ValueObject\Reporting\FileDiff;

final class FileDiffFactory
{
    public function __construct(
        private readonly DefaultDiffer $defaultDiffer,
        private readonly ConsoleDiffer $consoleDiffer,
        private readonly FilePathHelper $filePathHelper,
    ) {
    }

    /**
     * @param RectorWithLineChange[] $rectorsWithLineChanges
     */
    public function createFileDiffWithLineChanges(
        File $file,
        string $oldContent,
        string $newContent,
        array $rectorsWithLineChanges
    ): FileDiff {
        $relativeFilePath = $this->filePathHelper->relativePath($file->getFilePath());

        // always keep the most recent diff
        return new FileDiff(
            $relativeFilePath,
            $this->defaultDiffer->diff($oldContent, $newContent),
            $this->consoleDiffer->diff($oldContent, $newContent),
            $rectorsWithLineChanges
        );
    }

    public function createTempFileDiff(File $file): FileDiff
    {
        return $this->createFileDiffWithLineChanges($file, '', '', $file->getRectorWithLineChanges());
    }
}
