<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector;

use Iterator;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * @see RenameClassRector
 */
final class RenameParentTest extends AbstractRectorTestCase
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
        return $this->yieldFilePathsFromDirectory(__DIR__ . '/FixtureRenameParent');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/rename_parent.php';
    }
}
