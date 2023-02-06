<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * @see RenameClassRector
 */
final class RenameMissingParentTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureRenameMissingParent');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/rename_missing_parent.php';
    }
}
