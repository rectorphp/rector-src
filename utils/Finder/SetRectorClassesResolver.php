<?php

declare(strict_types=1);

namespace Rector\Utils\Finder;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Rector\Config\Level\CodeQualityLevel;
use Rector\Config\Level\DeadCodeLevel;
use Rector\Config\Level\TypeDeclarationLevel;
use Rector\Contract\Rector\RectorInterface;
use Rector\Set\ValueObject\SetList;

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
        // special level cases
        $setFileRealPath = realpath($setFile);

        if ($setFileRealPath === realpath(SetList::DEAD_CODE)) {
            return DeadCodeLevel::RULES;
        }

        if ($setFileRealPath === realpath(SetList::TYPE_DECLARATION)) {
            return TypeDeclarationLevel::RULES;
        }

        if ($setFileRealPath === realpath(SetList::CODE_QUALITY)) {
            return CodeQualityLevel::RULES;
        }

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
