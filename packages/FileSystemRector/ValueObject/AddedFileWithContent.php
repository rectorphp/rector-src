<?php

declare(strict_types=1);

namespace Rector\FileSystemRector\ValueObject;

use Rector\Core\Exception\ShouldNotHappenException;
use Rector\FileSystemRector\Contract\AddedFileInterface;

final class AddedFileWithContent implements AddedFileInterface
{
    public function __construct(
        private string $filePath,
        private string $fileContent
    ) {
        if ($filePath === $fileContent) {
            throw new ShouldNotHappenException('File path and content are the same, probably a bug');
        }
    }

    /**
     * @return string
     */
    public function getRealPath()
    {
        $realpath = realpath($this->filePath);

        if ($realpath === false) {
            throw ShouldNotHappenException();
        }

        return $realpath;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function getFileContent(): string
    {
        return $this->fileContent;
    }
}
