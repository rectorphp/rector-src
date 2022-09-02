<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\Rector\ClassMethod\RenameAnnotationRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RenameAnnotationEverywhereRectorTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureRenameEverywhere');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/rename_everywhere.php';
    }
}
