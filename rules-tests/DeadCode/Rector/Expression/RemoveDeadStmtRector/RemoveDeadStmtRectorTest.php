<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhp;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RemoveDeadStmtRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    #[RequiresPhp('>= 8.5.0')]
    #[DataProvider('provideDataPhp85')]
    public function testPhp85(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideDataPhp85(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixturePhp85');
    }

    #[DataProvider('provideDataForTestKeepComments')]
    public function testKeepComments(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideDataForTestKeepComments(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/FixtureRemovedComments');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
