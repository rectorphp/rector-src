<?php

declare(strict_types=1);

namespace Rector\Core\FileSystem;

use Symplify\SmartFileSystem\SmartFileSystem;

final class FilePathHelper
{
    public function __construct(
        private readonly SmartFileSystem $smartFileSystem,
    ) {
    }

    public function relativePath(string $fileRealPath): string
    {
        $normalizedFileRealPath = $this->normalizePath($fileRealPath);

        $relativeFilePath = $this->smartFileSystem->makePathRelative(
            $normalizedFileRealPath,
            (string) realpath(getcwd())
        );

        return \rtrim($relativeFilePath, '/');
    }

    private function normalizePath(string $filePath): string
    {
        return \str_replace('\\', '/', $filePath);
    }
}
