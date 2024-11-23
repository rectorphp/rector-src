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
    private const LINE_RANGE_REGEX = '#\@\@ (?<LINE_RANGE>\-\d+,\d+ \+\d+,\d+) \@\@#';

    public static function clean(string $diff): string
    {
        $diffLines = NewLineSplitter::split($diff);

        foreach ($diffLines as $key => $diffLine) {
            $diffLines[$key] = Strings::replace($diffLine, self::LINE_RANGE_REGEX, fn(array $match): string => '@@ @@');
        }

        return implode(PHP_EOL, $diffLines);
    }
}
