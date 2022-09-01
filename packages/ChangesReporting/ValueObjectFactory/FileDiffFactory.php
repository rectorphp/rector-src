<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\ValueObjectFactory;

use Rector\Core\Console\Formatter\ConsoleDiffer;
use Rector\Core\Differ\DefaultDiffer;
use Rector\Core\FileSystem\FilePathHelper;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Reporting\FileDiff;

final class FileDiffFactory
{
    public function __construct(
        private readonly DefaultDiffer $defaultDiffer,
        private readonly ConsoleDiffer $consoleDiffer,
        private readonly FilePathHelper $filePathHelper,
    ) {
    }

    public function createFileDiff(File $file, string $oldContent, string $newContent): FileDiff
    {
        $relativeFilePath = $this->filePathHelper->relativePath($file->getFilePath());

        // always keep the most recent diff
        return new FileDiff(
            $relativeFilePath,
            $this->defaultDiffer->diff($oldContent, $newContent),
            $this->consoleDiffer->diff($oldContent, $newContent),
            $file->getRectorWithLineChanges()
        );
    }
}
