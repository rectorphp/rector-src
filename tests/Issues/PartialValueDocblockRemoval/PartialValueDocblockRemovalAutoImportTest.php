<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\PartialValueDocblockRemoval;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class PartialValueDocblockRemovalAutoImportTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/auto_import.php';
    }
}
