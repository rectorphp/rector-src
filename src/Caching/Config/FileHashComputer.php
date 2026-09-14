<?php

declare(strict_types=1);

namespace Rector\Caching\Config;

use Rector\Application\VersionResolver;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Exception\ShouldNotHappenException;
use Rector\FileSystem\FilePathHelper;

/**
 * Inspired by https://github.com/symplify/easy-coding-standard/blob/e598ab54686e416788f28fcfe007fd08e0f371d9/packages/changed-files-detector/src/FileHashComputer.php
 */
final readonly class FileHashComputer
{
    public function __construct(
        private FilePathHelper $filePathHelper
    ) {
    }

    public function compute(string $filePath): string
    {
        $this->ensureIsPhp($filePath);

        $parametersHash = SimpleParameterProvider::hashForCacheInvalidation();

        // the config path is relative to the project: an absolute one ties the hash, and with it
        // the whole cache, to a single directory on a single machine. Resolved first, because the
        // path arrives straight from `--config` and two spellings of one file must hash alike.
        $relativeFilePath = $this->filePathHelper->relativePath($this->resolvePath($filePath));

        return sha1($relativeFilePath . $parametersHash . VersionResolver::PACKAGE_VERSION);
    }

    private function resolvePath(string $filePath): string
    {
        $realPath = realpath($filePath);
        if ($realPath === false) {
            return $filePath;
        }

        return $realPath;
    }

    private function ensureIsPhp(string $filePath): void
    {
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        if ($fileExtension === 'php') {
            return;
        }

        throw new ShouldNotHappenException(sprintf(
            // getRealPath() cannot be used, as it breaks in phar
            'Provide only PHP file, ready for Dependency Injection. "%s" given',
            $filePath
        ));
    }
}
