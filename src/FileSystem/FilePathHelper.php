<?php

declare(strict_types=1);

namespace Rector\Core\FileSystem;

use Symfony\Component\Filesystem\Filesystem;

final class FilePathHelper
{
    public function __construct(
        private readonly Filesystem $filesystem,
    ) {
    }

    public function relativePath(string $fileRealPath): string
    {
        $normalizedFileRealPath = $this->normalizePath($fileRealPath);

        $relativeFilePath = $this->filesystem->makePathRelative(
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
