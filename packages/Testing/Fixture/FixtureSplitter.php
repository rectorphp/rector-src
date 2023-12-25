<?php
declare(strict_types=1);

namespace Rector\Testing\Fixture;

use Nette\Utils\FileSystem;

/**
 * @api
 */
final class FixtureSplitter
{
    public static function containsSplit(string $fixtureFileContent): bool
    {
        return str_contains($fixtureFileContent, "-----\n") || str_contains($fixtureFileContent, "-----\r\n");
    }

    /**
     * @return array<int, string>
     */
    public static function split(string $filePath): array
    {
        $fixtureFileContents = FileSystem::read($filePath);

        return self::splitFixtureFileContents($fixtureFileContents);
    }

    /**
     * @return array<int, string>
     */
    public static function splitFixtureFileContents(string $fixtureFileContents): array
    {
        $fixtureFileContents = str_replace(PHP_EOL, "\n", $fixtureFileContents);
        $posixContents = explode("-----\n", $fixtureFileContents);
        if (isset($posixContents[1])) {
            return $posixContents;
        }

        return $posixContents;
    }
}
