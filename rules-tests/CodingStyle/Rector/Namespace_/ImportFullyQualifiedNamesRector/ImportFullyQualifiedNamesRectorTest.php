<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\Namespace_\ImportFullyQualifiedNamesRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * @see \Rector\PostRector\Rector\NameImportingPostRector
 */
final class ImportFullyQualifiedNamesRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    #[DataProvider('provideDataFunction')]
    #[DataProvider('provideDataGeneric')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public static function provideDataFunction(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureFunction');
    }

    public static function provideDataGeneric(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureGeneric');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/import_config.php';
    }
}
