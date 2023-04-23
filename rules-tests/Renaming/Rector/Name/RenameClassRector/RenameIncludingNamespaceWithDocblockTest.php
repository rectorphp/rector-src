<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RenameIncludingNamespaceWithDocblockTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureIncludingNamespaceWithDocblock');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule_rename_including_namespace_with_docblock.php';
    }
}
