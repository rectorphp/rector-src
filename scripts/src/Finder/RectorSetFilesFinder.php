<?php

declare(strict_types=1);

namespace Rector\Scripts\Finder;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Webmozart\Assert\Assert;

final class RectorSetFilesFinder
{
    /**
     * @param string[] $configDirs
     * @return string[]
     */
    public function find(array $configDirs): array
    {
        Assert::allString($configDirs);
        Assert::allDirectory($configDirs);

        // find set files
        $finder = Finder::create()->in($configDirs)
            ->files()
            ->name('*.php');

        /** @var SplFileInfo[] $setFileInfos */
        $setFileInfos = iterator_to_array($finder->getIterator());

        $setFiles = [];
        foreach ($setFileInfos as $setFileInfo) {
            $setFiles[] = $setFileInfo->getRealPath();
        }

        return $setFiles;
    }
}
