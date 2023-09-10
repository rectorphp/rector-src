<?php

declare(strict_types=1);

namespace Rector\NodeNameResolver\Regex;

final class RegexPatternDetector
{
    /**
     * @var string[]
     *
     * This prevents miss matching like "aMethoda"
     */
    private const POSSIBLE_DELIMITERS = ['#', '~', '/'];

    public function isRegexPattern(string $name): bool
    {
        if (strlen($name) <= 2) {
            return false;
        }

        $firstChar = $name[0];
        $lastChar = $name[strlen($name) - 1];
        if ($firstChar !== $lastChar) {
            return false;
        }

        return in_array($firstChar, self::POSSIBLE_DELIMITERS, true);
    }
}
