<?php

declare(strict_types=1);

namespace Rector\Configuration;

use Rector\Exception\Configuration\InvalidConfigurationException;
use Rector\Set\ValueObject\LevelSetList;
use Rector\ValueObject\PhpVersion;

final class PhpLevelSetResolver
{
    public static function resolveFromPhpVersion(int $phpVersion): string
    {
        return match ($phpVersion) {
            PhpVersion::PHP_53 => LevelSetList::UP_TO_PHP_53,
            PhpVersion::PHP_54 => LevelSetList::UP_TO_PHP_54,
            PhpVersion::PHP_55 => LevelSetList::UP_TO_PHP_55,
            PhpVersion::PHP_56 => LevelSetList::UP_TO_PHP_56,
            PhpVersion::PHP_70 => LevelSetList::UP_TO_PHP_70,
            PhpVersion::PHP_71 => LevelSetList::UP_TO_PHP_71,
            PhpVersion::PHP_72 => LevelSetList::UP_TO_PHP_72,
            PhpVersion::PHP_73 => LevelSetList::UP_TO_PHP_73,
            PhpVersion::PHP_74 => LevelSetList::UP_TO_PHP_74,
            PhpVersion::PHP_80 => LevelSetList::UP_TO_PHP_80,
            PhpVersion::PHP_81 => LevelSetList::UP_TO_PHP_81,
            PhpVersion::PHP_82 => LevelSetList::UP_TO_PHP_82,
            PhpVersion::PHP_83 => LevelSetList::UP_TO_PHP_83,
            default => throw new InvalidConfigurationException(sprintf(
                'Could not resolve PHP level set list for "%s"',
                $phpVersion
            )),
        };
    }
}
