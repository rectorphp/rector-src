<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\IndentationCrash;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class TabIndentationCrashTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureTab');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/tab_configured_rule.php';
    }
}
