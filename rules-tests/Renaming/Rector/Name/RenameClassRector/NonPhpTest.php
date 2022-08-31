<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector;

use Iterator;
use Rector\Core\ValueObject\StaticNonPhpFileSuffixes;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class NonPhpTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilePathsFromDirectory(
            __DIR__ . '/FixtureRenameNonPhp',
            StaticNonPhpFileSuffixes::getSuffixRegexPattern()
        );
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/non_php_config.php';
    }
}
