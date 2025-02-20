<?php

declare(strict_types=1);

namespace Rector\Tests\ValueObject\Reporting;

use PHPUnit\Framework\TestCase;
use Rector\ValueObject\Reporting\FileDiff;

final class FileDiffTest extends TestCase
{
    public function testGetFirstLineNumberShouldReturnFirstLineNumberRegardingHunk(): void
    {
        $fileDiff = new FileDiff(
            'some/file.php',
            '--- Original\n+++ New\n@@ -38,5 +39,6 @@\nreturn true;\n}\n',
            'diff console formatted'
        );
        $this->assertSame(38, $fileDiff->getFirstLineNumber());
    }

    public function testGetFirstLineNumberShouldBeNullWhenHunkIsInvalid(): void
    {
        $fileDiff = new FileDiff(
            'some/file.php',
            '--- Original\n+++ New\n@@@@\nreturn true;\n}\n',
            'diff console formatted'
        );
        $this->assertNull($fileDiff->getFirstLineNumber());
    }
}
