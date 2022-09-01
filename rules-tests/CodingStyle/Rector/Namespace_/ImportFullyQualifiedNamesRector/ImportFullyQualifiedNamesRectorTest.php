<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\Namespace_\ImportFullyQualifiedNamesRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

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

    /**
     * @return Iterator<array<string>>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/Fixture');
    }

    /**
     * @return Iterator<SmartFileInfo>
     */
    public function provideDataFunction(): Iterator
    {
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/FixtureFunction');
    }

    /**
     * @return Iterator<SmartFileInfo>
     */
    public function provideDataGeneric(): Iterator
    {
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/FixtureGeneric');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/import_config.php';
    }
}
