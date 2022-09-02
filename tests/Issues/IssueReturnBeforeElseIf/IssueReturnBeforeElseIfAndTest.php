<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\IssueReturnBeforeElseIf;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class IssueReturnBeforeElseIfAndTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureAnd');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule_and.php';
    }
}
