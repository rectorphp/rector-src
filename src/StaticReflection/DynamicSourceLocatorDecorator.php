<?php

declare(strict_types=1);

namespace Rector\StaticReflection;

use Rector\FileSystem\FileAndDirectoryFilter;
use Rector\FileSystem\FilesystemTweaker;
use Rector\NodeTypeResolver\Reflection\BetterReflection\SourceLocatorProvider\DynamicSourceLocatorProvider;

/**
 * @see https://phpstan.org/blog/zero-config-analysis-with-static-reflection
 * @see https://github.com/rectorphp/rector/issues/3490
 */
final readonly class DynamicSourceLocatorDecorator
{
    public function __construct(
        private DynamicSourceLocatorProvider $dynamicSourceLocatorProvider,
        private FileAndDirectoryFilter $fileAndDirectoryFilter,
        private FilesystemTweaker $filesystemTweaker
    ) {
    }

    /**
     * @param string[] $paths
     * @return string[]
     */
    public function addPaths(array $paths): array
    {
        if ($paths === []) {
            return [];
        }

        $paths = $this->filesystemTweaker->resolveWithFnmatch($paths);
        $files = $this->fileAndDirectoryFilter->filterFiles($paths);

        $this->dynamicSourceLocatorProvider->addFiles($files);

        $directories = $this->fileAndDirectoryFilter->filterDirectories($paths);
        $this->dynamicSourceLocatorProvider->addDirectories($directories);

        return array_merge($files, $directories);
    }

    public function arePathsEmpty(): bool
    {
        return $this->dynamicSourceLocatorProvider->arePathsEmpty();
    }
}
