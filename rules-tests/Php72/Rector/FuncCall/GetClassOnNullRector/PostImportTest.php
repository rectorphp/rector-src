<?php

declare(strict_types=1);

namespace Rector\Tests\Php72\Rector\FuncCall\GetClassOnNullRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class PostImportTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixturePostImport');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/auto_import.php';
    }
}
