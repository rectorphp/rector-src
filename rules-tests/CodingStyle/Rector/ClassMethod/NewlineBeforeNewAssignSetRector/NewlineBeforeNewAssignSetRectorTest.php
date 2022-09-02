<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class NewlineBeforeNewAssignSetRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        if ($this->isWindows()) {
            $this->markTestSkipped('doesnt work on windows, see https://github.com/rectorphp/rector/issues/6572');
        }

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
        return __DIR__ . '/config/configured_rule.php';
    }
}
