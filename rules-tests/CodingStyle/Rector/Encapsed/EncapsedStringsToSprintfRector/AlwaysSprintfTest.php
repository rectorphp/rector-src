<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class AlwaysSprintfTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureAlwaysSprintf');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/always_sprintf.php';
    }
}
