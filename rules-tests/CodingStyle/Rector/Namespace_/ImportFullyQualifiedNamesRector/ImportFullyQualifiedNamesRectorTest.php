<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\Namespace_\ImportFullyQualifiedNamesRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * @see \Rector\PostRector\Rector\NameImportingPostRector
 */
final class ImportFullyQualifiedNamesRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     * @dataProvider provideDataFunction()
     * @dataProvider provideDataGeneric()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideDataFunction(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureFunction');
    }

    public function provideDataGeneric(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureGeneric');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/import_config.php';
    }
}
