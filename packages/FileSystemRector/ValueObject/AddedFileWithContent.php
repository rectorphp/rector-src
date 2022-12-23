<?php

declare(strict_types=1);

namespace Rector\FileSystemRector\ValueObject;

use Rector\Core\Exception\ShouldNotHappenException;
use Rector\FileSystemRector\Contract\AddedFileInterface;

/**
 * @api
 */
final class AddedFileWithContent implements AddedFileInterface
{
    public function __construct(
        private readonly string $filePath,
        private readonly string $fileContent
    ) {
        if ($filePath === $fileContent) {
            throw new ShouldNotHappenException('File path and content are the same, probably a bug');
        }
    }

    public function getRealPath(): string
    {
        $realPath = realpath($this->filePath);
        if ($realPath === false) {
            throw new ShouldNotHappenException();
        }

        return $realPath;
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
