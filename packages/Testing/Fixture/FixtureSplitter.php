<?php
declare(strict_types=1);

namespace Rector\Testing\Fixture;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Webmozart\Assert\Assert;

final class FixtureSplitter
{
    /**
     * @var string
     * @see https://regex101.com/r/zZDoyy/1
     */
    private const SPLIT_LINE_REGEX = '#-----\n#';

    /**
     * @return array<string, string>
     */
    public static function loadFileAndSplitInputAndExpected(string $filePath): array
    {
        Assert::fileExists($filePath);
        $fixtureFileContents = FileSystem::read($filePath);

        return Strings::split($fixtureFileContents, self::SPLIT_LINE_REGEX);
    }
}
