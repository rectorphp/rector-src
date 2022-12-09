<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\IndentationCrash;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class CodeQualityCodingStyleIndentationCrashTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureCodeQualityCodingStyle');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/code_quality_coding_style_configured_rule.php';
    }
}
