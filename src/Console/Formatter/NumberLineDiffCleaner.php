<?php

declare(strict_types=1);

namespace Rector\Console\Formatter;

use Nette\Utils\Strings;
use Rector\Util\NewLineSplitter;

class NumberLineDiffCleaner
{
    /**
     * @var string
     * @see https://regex101.com/r/zHJEfJ/1
     */
    private const LINE_RANGE_REGEX = '#^\<fg=cyan\>@@ (?<LINE_RANGE>\-\d+,\d+ \+\d+,\d+) @@\<\/fg=cyan\>$#';

    public static function clean(string $diff): string
    {
        $diffLines = NewLineSplitter::split($diff);

        foreach ($diffLines as $key => $diffLine) {
            $diffLines[$key] = Strings::replace($diffLine, self::LINE_RANGE_REGEX, '<fg=cyan>@@ @@</fg=cyan>');
        }

        return implode(PHP_EOL, $diffLines);
    }
}
