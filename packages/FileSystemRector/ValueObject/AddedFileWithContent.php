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

<<<<<<< HEAD
    public function getRealPath(): string
    {
        $realpath = realpath($this->filePath);
        if ($realpath === false) {
            throw new ShouldNotHappenException();
        }

        return $realpath;
=======
    /**
     * @return string
     */
    public function getRealPath()
    {
<<<<<<< HEAD
        return realpath($this->filePath);
>>>>>>> d5ea8a664 (apply PlatformAgnosticAssertions on MoveValueObjectsToValueObjectDirectoryRectorTest)
=======
        $realpath = realpath($this->filePath);

        if ($realpath === false) {
            throw new ShouldNotHappenException();
        }

        return $realpath;
>>>>>>> c169f9af3 (eleminate false-return type from AddedFileWithContent::getRealPath())
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
