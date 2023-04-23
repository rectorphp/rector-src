<?php
declare(strict_types=1);

namespace Rector\Testing\Fixture;

use Nette\Utils\Strings;

/**
 * @api
 */
final class FixtureSplitter
{
    /**
     * @api
     * @var string
     * @see https://regex101.com/r/zZDoyy/1
     */
    public const SPLIT_LINE_REGEX = '#\-\-\-\-\-\r?\n#';

    public static function containsSplit(string $fixtureFileContent): bool
    {
        return Strings::match($fixtureFileContent, self::SPLIT_LINE_REGEX) !== null;
    }

    /**
     * @return array<string, string>
     */
    public static function split(string $fixtureFileContents): array
    {
        return Strings::split($fixtureFileContents, self::SPLIT_LINE_REGEX);
    }
}
