<?php

declare(strict_types=1);

namespace Rector\Testing\Fixture;

use Iterator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symplify\SmartFileSystem\SmartFileInfo;

final class FixtureFileFinder
{
    /**
     * @return Iterator<array<int, SmartFileInfo>>
     */
    public static function yieldDirectory(string $directory, string $suffix = '*.php.inc'): Iterator
    {
        $fileInfos = self::findFilesInDirectory($directory, $suffix);

        foreach ($fileInfos as $fileInfo) {
            // @todo is this one needed? maybe symfony is good enough :)
            $smartFileInfo = new SmartFileInfo($fileInfo->getRealPath());
            yield [$smartFileInfo];
        }
    }

    /**
     * @return SplFileInfo[]
     */
    private static function findFilesInDirectory(string $directory, string $suffix): array
    {
        $finder = (new Finder())
            ->in($directory)
            ->files()
            ->name($suffix);

        $fileInfos = iterator_to_array($finder);
        return array_values($fileInfos);
    }
}
