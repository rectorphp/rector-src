<?php

declare(strict_types=1);

namespace Rector\Testing\Fixture;

use Iterator;
use Symfony\Component\Finder\Finder;

final class FixtureFileFinder
{
    /**
     * @return Iterator<array<int, string>>
     */
    public static function yieldDirectory(string $directory, string $suffix = '*.php.inc'): Iterator
    {
        $finder = (new Finder())
            ->in($directory)
            ->files()
            ->name($suffix);

        foreach ($finder as $fileInfo) {
            yield [$fileInfo->getRealPath()];
        }
    }
}
