<?php

declare(strict_types=1);

namespace Rector\Scripts\Finder;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Webmozart\Assert\Assert;

final class FixtureFinder
{
    /**
     * @param string[] $directories
     * @return SplFileInfo[]
     */
    public static function find(array $directories): array
    {
        Assert::allDirectory($directories);

        $finder = (new Finder())
            ->files()
            ->in($directories)
            ->name('*.php.inc')
            ->sortByName();

        return iterator_to_array($finder->getIterator());
    }
}
