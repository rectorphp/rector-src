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
        return str_contains($fixtureFileContent, '-----' . PHP_EOL);
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
        return explode("-----" . PHP_EOL, $fixtureFileContents);
    }
}
