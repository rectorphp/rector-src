<?php

declare(strict_types=1);

namespace Rector\Utils\Finder;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Rector\Contract\Rector\RectorInterface;

final class SetRectorClassesResolver
{
    /**
     * @see https://regex101.com/r/HtsmKC/1
     * @var string
     */
    private const RECTOR_CLASS_REGEX = '#use (?<class_name>[\\\\\w]+Rector)#m';

    /**
     * @return array<class-string<RectorInterface>>
     */
    public static function resolve(string $setFile): array
    {
        $rectorClasses = [];

        $setFileContents = FileSystem::read($setFile);
        $matches = Strings::matchAll($setFileContents, self::RECTOR_CLASS_REGEX);

        foreach ($matches as $match) {
            $rectorClassName = $match['class_name'];
            if ($rectorClassName === 'Rector\Config\Rector') {
                continue;
            }

            $rectorClasses[] = $rectorClassName;
        }

        return $rectorClasses;
    }
}
