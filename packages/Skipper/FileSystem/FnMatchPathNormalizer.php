<?php

declare(strict_types=1);

namespace Rector\Skipper\FileSystem;

use Nette\Utils\Strings;
use Rector\Skipper\Enum\AsteriskMatch;

/**
 * @see \Rector\Tests\Skipper\FileSystem\FnMatchPathNormalizerTest
 */
final class FnMatchPathNormalizer
{
    public function normalizeForFnmatch(string $path): string
    {
        // ends with *
        if (Strings::match($path, AsteriskMatch::ONLY_ENDS_WITH_ASTERISK_REGEX) !== null) {
            return '*' . $path;
        }

        // starts with *
        if (Strings::match($path, AsteriskMatch::ONLY_STARTS_WITH_ASTERISK_REGEX) !== null) {
            return $path . '*';
        }

        if (\str_contains($path, '..')) {
            $path = realpath($path);
            if ($path === false) {
                return '';
            }
        }

        return $path;
    }
}
