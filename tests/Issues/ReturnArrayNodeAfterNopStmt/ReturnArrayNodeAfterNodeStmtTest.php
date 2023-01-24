<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\ReturnArrayNodeAfterNopStmt;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ReturnArrayNodeAfterNodeStmtTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureArrayNextNop');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/array_next_nop_configured_rule.php';
    }
}
