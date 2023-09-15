<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * @see \Rector\PostRector\Rector\NameImportingPostRector
 */
final class AutoImportNamesTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        if (str_ends_with($filePath, 'auto_import_conflict_name_all_fqcn_same_namespace.php.inc')) {
            // @todo fix later
            return;
        }

        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureAutoImportNames');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/auto_import_names.php';
    }
}
