<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\AutoImport;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class AutoImportInsertSortedTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/InsertSortedFixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/insert_sorted_configured_rule.php';
    }
}
