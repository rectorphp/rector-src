<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RemoveDeadStmtRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return Iterator<array<string>>
     */
    public static function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    #[DataProvider('provideDataForTestKeepComments')]
    public function testKeepComments(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideDataForTestKeepComments(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureRemovedComments');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
