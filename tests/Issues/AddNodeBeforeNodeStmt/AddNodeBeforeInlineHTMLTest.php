<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\AddNodeBeforeNodeStmt;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class AddNodeBeforeInlineHTMLTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureBeforeInlineHTML');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/before_inline_html_configured_rule.php';
    }
}
