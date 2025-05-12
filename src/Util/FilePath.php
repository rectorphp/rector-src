<?php

declare(strict_types=1);

namespace Rector\Util;

use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;

/**
 * @see \Rector\Tests\Util\FilePathTest
 */
final class FilePath
{
    public static function fileIsInRectorPathOrSource(string $file): bool
    {
        foreach (SimpleParameterProvider::provideArrayParameter(Option::SOURCE) as $source) {
            if ($source === $file) {
                return true;
            }
        }

        $paths = SimpleParameterProvider::provideArrayParameter(Option::PATHS);
        $pathToCheck = realpath(dirname($file)) . DIRECTORY_SEPARATOR;

        foreach ($paths as $path) {
            if (str_starts_with(realpath($path) . DIRECTORY_SEPARATOR, $pathToCheck)) {
                return true;
            }
        }

        return false;
    }
}
