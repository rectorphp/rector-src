<?php

declare(strict_types=1);

namespace Rector\Core\StaticReflection;

use Rector\Core\FileSystem\FileAndDirectoryFilter;
use Rector\Core\FileSystem\PhpFilesFinder;
use Rector\NodeTypeResolver\Reflection\BetterReflection\SourceLocatorProvider\DynamicSourceLocatorProvider;

/**
 * @see https://phpstan.org/blog/zero-config-analysis-with-static-reflection
 * @see https://github.com/rectorphp/rector/issues/3490
 */
final class DynamicSourceLocatorDecorator
{
    public function __construct(
        private readonly DynamicSourceLocatorProvider $dynamicSourceLocatorProvider,
        private readonly PhpFilesFinder $phpFilesFinder,
        private readonly FileAndDirectoryFilter $fileAndDirectoryFilter
    ) {
    }

    /**
     * @param string[] $paths
     */
    public function addPaths(array $paths): void
    {
        if ($paths === []) {
            return;
        }

        $files = $this->fileAndDirectoryFilter->filterFiles($paths);
        $this->dynamicSourceLocatorProvider->addFiles($files);

        $directories = $this->fileAndDirectoryFilter->filterDirectories($paths);
        foreach ($directories as $directory) {
            $filesInDirectory = $this->phpFilesFinder->findInPaths([$directory]);
            $this->dynamicSourceLocatorProvider->addFilesByDirectory($directory, $filesInDirectory);
        }
    }
}
