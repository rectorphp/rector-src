<?php

declare(strict_types=1);

namespace Rector\Tests\Tia;

use JMac\Testing\PhpUnit\Tia\Contracts\Resolver;

/**
 * Test impact analysis maps a changed file to a test via the recorded coverage graph. Fixture files
 * never appear in that graph: "*.php.inc" is not executed code, and "Source/" or "Expected/" files are
 * loaded by reflection, not by the test itself. Without this resolver, a fixture-only change would look
 * like it affects nothing and the test would be skipped as unaffected.
 *
 * Every changed file below a test directory is therefore mapped to the closest test class above it.
 */
final class FixtureResolver implements Resolver
{
    /**
     * @var string[]
     */
    private const TEST_DIRECTORIES = ['tests/', 'rules-tests/', 'utils/phpstan/tests/'];

    /**
     * @return list<string>
     */
    public function resolve(string $projectRoot, string $changedRelativePath): array
    {
        if (! $this->isInTestDirectory($changedRelativePath)) {
            return [];
        }

        $rootDirectory = rtrim(str_replace('\\', '/', $projectRoot), '/');
        $directory = dirname($rootDirectory . '/' . $changedRelativePath);

        while (str_starts_with($directory . '/', $rootDirectory . '/') && $directory !== $rootDirectory) {
            $testFilePaths = glob($directory . '/*Test.php');

            if ($testFilePaths !== false && $testFilePaths !== []) {
                return array_values($testFilePaths);
            }

            $directory = dirname($directory);
        }

        return [];
    }

    private function isInTestDirectory(string $changedRelativePath): bool
    {
        foreach (self::TEST_DIRECTORIES as $testDirectory) {
            if (str_starts_with($changedRelativePath, $testDirectory)) {
                return true;
            }
        }

        return false;
    }
}
