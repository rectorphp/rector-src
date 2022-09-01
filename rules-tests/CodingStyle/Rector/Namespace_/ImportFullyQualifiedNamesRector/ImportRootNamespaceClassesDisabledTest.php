<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\Namespace_\ImportFullyQualifiedNamesRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * @see \Rector\PostRector\Rector\NameImportingPostRector
 */
final class ImportRootNamespaceClassesDisabledTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return Iterator
     */
    public function provideData(): iterable
    {
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/FixtureRoot');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/not_import_short_classes.php';
    }
}
