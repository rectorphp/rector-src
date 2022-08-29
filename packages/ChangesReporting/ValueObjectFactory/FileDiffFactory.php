<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\ValueObjectFactory;

use Rector\Core\Differ\DefaultDiffer;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Symplify\PackageBuilder\Console\Output\ConsoleDiffer;
use Symplify\SmartFileSystem\SmartFileSystem;

final class FileDiffFactory
{
    public function __construct(
        private readonly DefaultDiffer $defaultDiffer,
        private readonly ConsoleDiffer $consoleDiffer,
        private readonly SmartFileSystem $smartFileSystem,
    ) {
    }

    public function createFileDiff(File $file, string $oldContent, string $newContent): FileDiff
    {
        $relativeFilePath = $this->smartFileSystem->makePathRelative(
            $file->getFilePath(),
            (string) realpath(getcwd())
        );
        $relativeFilePath = \rtrim($relativeFilePath, '/');

        // always keep the most recent diff
        return new FileDiff(
            $relativeFilePath,
            $this->defaultDiffer->diff($oldContent, $newContent),
            $this->consoleDiffer->diff($oldContent, $newContent),
            $file->getRectorWithLineChanges()
        );
    }
}
