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
        if (str_ends_with($filePath, 'skip_pipe_operator.php.inc') && PHP_VERSION_ID < 80500) {
            $this->markTestSkipped('test contains php 8.5 syntax early before transformation');
        }

        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
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
