<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class KeepUnusedImportTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureKeepUnusedImport');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/keep_unused_import.php';
    }
}
