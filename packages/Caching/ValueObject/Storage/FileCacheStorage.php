<?php

declare(strict_types=1);

namespace Rector\Caching\ValueObject\Storage;

use Nette\Utils\Random;
use PHPStan\Cache\CacheItem;
use PHPStan\File\FileWriter;
use PHPStan\ShouldNotHappenException;

/**
 * Inspired by
 * https://github.com/phpstan/phpstan-src/commit/4df7342f3a0aaef4bcd85456dd20ca88d38dd90d#diff-6dc14f6222bf150e6840ca44a7126653052a1cedc6a149b4e5c1e1a2c80eacdc
 */
final class FileCacheStorage
{
    public function __construct(
        private string $directory,
        private SmartFileSystem $smartFileSystem
    ) {
    }

    private function makeDir(string $directory) : void
    {
        if (\is_dir($directory)) {
            return;
        }
        $result = @\mkdir($directory, 0777);
        if ($result === \false) {
            \clearstatcache();
            if (\is_dir($directory)) {
                return;
            }
            $error = \error_get_last();
            throw new \InvalidArgumentException(\sprintf('Failed to create directory "%s" (%s).', $this->directory, $error !== null ? $error['message'] : 'unknown cause'));
        }
    }

    /**
     * @param string $key
     * @param string $variableKey
     * @return mixed|null
     */
    public function load(string $key, string $variableKey)
    {
        return (function (string $key, string $variableKey) {
            [, , $filePath] = $this->getFilePaths($key);
            if (!\is_file($filePath)) {
                return null;
            }
            $cacheItem = (require $filePath);
            if (!$cacheItem instanceof CacheItem) {
                return null;
            }
            if (!$cacheItem->isVariableKeyValid($variableKey)) {
                return null;
            }
            return $cacheItem->getData();
        })($key, $variableKey);
    }

    /**
     * @param string $key
     * @param string $variableKey
     * @param mixed $data
     * @return void
     */
    public function save(string $key, string $variableKey, $data) : void
    {
        [$firstDirectory, $secondDirectory, $path] = $this->getFilePaths($key);
        $this->makeDir($this->directory);
        $this->makeDir($firstDirectory);
        $this->makeDir($secondDirectory);
        $tmpPath = \sprintf('%s/%s.tmp', $this->directory, Random::generate());
        $errorBefore = \error_get_last();
        $exported = @\var_export(new CacheItem($variableKey, $data), \true);
        $errorAfter = \error_get_last();
        if ($errorAfter !== null && $errorBefore !== $errorAfter) {
            throw new ShouldNotHappenException(\sprintf('Error occurred while saving item %s (%s) to cache: %s', $key, $variableKey, $errorAfter['message']));
        }
        FileWriter::write($tmpPath, \sprintf("<?php declare(strict_types = 1);\n\nreturn %s;", $exported));
        $renameSuccess = @\rename($tmpPath, $path);
        if ($renameSuccess) {
            return;
        }
        @\unlink($tmpPath);
        if (\DIRECTORY_SEPARATOR === '/' || !\file_exists($path)) {
            throw new \InvalidArgumentException(\sprintf('Could not write data to cache file %s.', $path));
        }
    }

    public function clean(string $cacheKey): void
    {
        [$firstDirectory, $secondDirectory, $path] = $this->getFilePaths($cacheKey);

        $this->smartFileSystem->remove([
            $firstDirectory,
            $secondDirectory,
            $path
        ]);
    }

    public function clear(): void
    {
        $this->smartFileSystem->remove($this->directory);
    }

    /**
     * @param string $key
     * @return array{string, string, string}
     */
    private function getFilePaths(string $key) : array
    {
        $keyHash = \sha1($key);
        $firstDirectory = \sprintf('%s/%s', $this->directory, \substr($keyHash, 0, 2));
        $secondDirectory = \sprintf('%s/%s', $firstDirectory, \substr($keyHash, 2, 2));
        $filePath = \sprintf('%s/%s.php', $secondDirectory, $keyHash);
        return [$firstDirectory, $secondDirectory, $filePath];
    }
}
