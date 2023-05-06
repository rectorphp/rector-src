<?php

declare(strict_types=1);

namespace Rector\Core\NonPhpFile;

use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\ChangesReporting\ValueObjectFactory\FileDiffFactory;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Contract\Rector\NonPhpRectorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Symfony\Component\Filesystem\Filesystem;

final class NonPhpFileProcessor implements FileProcessorInterface
{
    /**
     * @var string[]
     */
    private const SUFFIXES = ['neon', 'yaml', 'xml', 'yml', 'twig', 'latte', 'blade.php', 'tpl'];

    /**
     * @param NonPhpRectorInterface[] $nonPhpRectors
     */
    public function __construct(
        private readonly array $nonPhpRectors,
        private readonly FileDiffFactory $fileDiffFactory,
        private readonly ChangedFilesDetector $changedFilesDetector,
        private readonly Filesystem $filesystem,
        private readonly RemovedAndAddedFilesCollector $removedAndAddedFilesCollector,
    ) {
    }

    public function process(File $file, Configuration $configuration): ?FileDiff
    {
        if ($this->nonPhpRectors === []) {
            return null;
        }

        $oldFileContent = $file->getFileContent();
        $newFileContent = $file->getFileContent();

        foreach ($this->nonPhpRectors as $nonPhpRector) {
            $newFileContent = $nonPhpRector->refactorFileContent($file->getFileContent());
            if ($oldFileContent === $newFileContent) {
                continue;
            }

            $file->changeFileContent($newFileContent);
        }

        if ($oldFileContent === $newFileContent) {
            $this->changedFilesDetector->addCachableFile($file->getFilePath());

            return null;
        }

        $fileDiff = $this->fileDiffFactory->createFileDiff($file, $oldFileContent, $newFileContent);

        $this->printFile($file, $configuration);

        return $fileDiff;
    }

    public function supports(File $file, Configuration $configuration): bool
    {
        // early assign to variable for increase performance
        // @see https://3v4l.org/FM3vY#focus=8.0.7 vs https://3v4l.org/JZW7b#focus=8.0.7
        $filePath = $file->getFilePath();

        // bug in path extension
        foreach ($this->getSupportedFileExtensions() as $fileExtension) {
            if (\str_ends_with($filePath, '.' . $fileExtension)) {
                return true;
            }
        }

        return false;
    }

    public function getSupportedFileExtensions(): array
    {
        return self::SUFFIXES;
    }

    private function printFile(File $file, Configuration $configuration): void
    {
        $filePath = $file->getFilePath();
        if ($this->removedAndAddedFilesCollector->isFileRemoved($filePath)) {
            // skip, because this file exists no more
            return;
        }

        if ($configuration->isDryRun()) {
            return;
        }

        $this->filesystem->dumpFile($filePath, $file->getFileContent());
    }
}
