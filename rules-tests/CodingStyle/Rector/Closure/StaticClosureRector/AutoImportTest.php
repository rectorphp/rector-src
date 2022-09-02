<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\Closure\StaticClosureRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class AutoImportTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureAutoImport');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/auto_import_configured_rule.php';
    }
}
