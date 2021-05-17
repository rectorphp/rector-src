<?php

declare(strict_types=1);

namespace Rector\Core\Application\FileSystem;

use Rector\FileSystemRector\Contract\AddedFileInterface;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Rector\FileSystemRector\ValueObject\AddedFileWithNodes;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RemovedAndAddedFilesCollector
{
    /**
     * @var SmartFileInfo[]
     */
    private array $removedFileInfos = [];

    /**
     * @var AddedFileInterface[]
     */
    private array $addedFiles = [];

    public function removeFile(SmartFileInfo $smartFileInfo): void
    {
        $this->removedFileInfos[] = $smartFileInfo;
    }

    /**
     * @return SmartFileInfo[]
     */
    public function getRemovedFiles(): array
    {
        return $this->removedFileInfos;
    }

    public function isFileRemoved(SmartFileInfo $smartFileInfo): bool
    {
        foreach ($this->removedFileInfos as $removedFileInfo) {
            if ($removedFileInfo->getPathname() !== $smartFileInfo->getPathname()) {
                continue;
            }

            return true;
        }

        return false;
    }

    public function addAddedFile(AddedFileInterface $addedFile): void
    {
        $this->addedFiles[] = $addedFile;
    }

    /**
     * @return AddedFileWithContent[]
     */
    public function getAddedFilesWithContent(): array
    {
        return array_filter(
            $this->addedFiles,
            fn (AddedFileInterface $addedFile): bool => $addedFile instanceof AddedFileWithContent
        );
    }

    /**
     * @return AddedFileWithNodes[]
     */
    public function getAddedFilesWithNodes(): array
    {
        return array_filter(
            $this->addedFiles,
            fn (AddedFileInterface $addedFile): bool => $addedFile instanceof AddedFileWithNodes
        );
    }

    public function getAffectedFilesCount(): int
    {
        return count($this->addedFiles) + count($this->removedFileInfos);
    }

    public function getAddedFileCount(): int
    {
        return count($this->addedFiles);
    }

    public function getRemovedFilesCount(): int
    {
        return count($this->removedFileInfos);
    }

    /**
     * For testing
     */
    public function reset(): void
    {
        $this->addedFiles = [];
        $this->removedFileInfos = [];
    }
}
