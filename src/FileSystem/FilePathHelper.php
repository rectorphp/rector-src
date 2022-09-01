<?php

declare(strict_types=1);

namespace Rector\Core\FileSystem;

use Symfony\Component\Filesystem\Filesystem;
use Webmozart\Assert\Assert;

final class FilePathHelper
{
    public function __construct(
        private readonly Filesystem $filesystem,
    ) {
    }

    public function relativePath(string $fileRealPath): string
    {
        if (! $this->filesystem->isAbsolutePath($fileRealPath)) {
            return $fileRealPath;
        }

        return $this->relativeFilePathFromDirectory($fileRealPath, getcwd());
    }

    /**
     * @api
     */
    public function relativeFilePathFromDirectory(string $fileRealPath, string $directory): string
    {
        Assert::directory($directory);

        $normalizedFileRealPath = $this->normalizePath($fileRealPath);

        $relativeFilePath = $this->filesystem->makePathRelative($normalizedFileRealPath, $directory);

        return rtrim($relativeFilePath, '/');
    }

    private function normalizePath(string $filePath): string
    {
        return \str_replace('\\', '/', $filePath);
    }
}
