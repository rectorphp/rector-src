<?php
declare(strict_types=1);

namespace Rector\Testing\Fixture;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Webmozart\Assert\Assert;

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
    public static function split(string $filePath): array
    {
        $fixtureFileContents = FileSystem::read($filePath);

        return Strings::split($fixtureFileContents, self::SPLIT_LINE_REGEX);
    }
}
