<?php

declare(strict_types=1);

namespace Rector\Caching\Config;

use Rector\Core\Exception\ShouldNotHappenException;

/**
 * Inspired by https://github.com/symplify/easy-coding-standard/blob/e598ab54686e416788f28fcfe007fd08e0f371d9/packages/changed-files-detector/src/FileHashComputer.php
 */
final class FileHashComputer
{
    public function compute(string $filePath): string
    {
        $this->ensureIsPhp($filePath);

        $hashedFile = sha1_file($filePath);

        if ($hashedFile === false) {
            throw new ShouldNotHappenException(sprintf('File %s cannot be hashed', $filePath));
        }

        return $hashedFile;
    }

    private function ensureIsPhp(string $filePath): void
    {
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        if ($fileExtension === 'php') {
            return;
        }

        throw new ShouldNotHappenException(sprintf(
            // getRealPath() cannot be used, as it breaks in phar
            'Provide only PHP file, ready for Laravel Dependency Injection. "%s" given',
            $filePath
        ));
    }
}
