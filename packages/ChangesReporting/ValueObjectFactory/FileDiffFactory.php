<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\ValueObjectFactory;

use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\Core\Console\Formatter\ConsoleDiffer;
use Rector\Core\Differ\DefaultDiffer;
use Rector\Core\FileSystem\FilePathHelper;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Reporting\FileDiff;

final class FileDiffFactory
{
    public function __construct(
        private readonly DefaultDiffer $defaultDiffer,
        private readonly ConsoleDiffer $consoleDiffer,
        private readonly FilePathHelper $filePathHelper,
    ) {
    }

    public function createFileDiff(File $file, string $oldContent, string $newContent, Configuration $configuration): FileDiff
    {
        return $this->createFileDiffWithLineChanges($file, $oldContent, $newContent, $file->getRectorWithLineChanges(), $configuration);
    }

    /**
     * @param RectorWithLineChange[] $rectorsWithLineChanges
     */
    public function createFileDiffWithLineChanges(
        File $file,
        string $oldContent,
        string $newContent,
        array $rectorsWithLineChanges,
        Configuration $configuration
    ): FileDiff {

        if ($configuration->getOutputFormat() === ConsoleOutputFormatter::NAME) {
            return $this->factory($file, $oldContent, $newContent, $this->consoleDiffer, $rectorsWithLineChanges);
        }
        return $this->factory($file, $oldContent, $newContent, $this->defaultDiffer, $rectorsWithLineChanges);
    }

    public function createTempFileDiff(File $file, Configuration $configuration): FileDiff
    {
        return $this->factory($file, '', '', $file->getRectorWithLineChanges(), $configuration);
    }

    /**
     * @param RectorWithLineChange[] $rectorsWithLineChanges
     */
    private function factory(
        File $file,
        string $oldContent,
        string $newContent,
        ConsoleDiffer|DefaultDiffer $differ,
        array $rectorsWithLineChanges
    ): FileDiff {
        $relativeFilePath = $this->filePathHelper->relativePath($file->getFilePath());

        return new FileDiff(
            $relativeFilePath,
            $differ->diff($oldContent, $newContent),
            $differ instanceof ConsoleDiffer,
            $rectorsWithLineChanges,
        );
    }
}
