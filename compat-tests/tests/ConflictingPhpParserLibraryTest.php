<?php declare(strict_types=1);

namespace BBurnichon\RectorIssue9437\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Version;

final class ConflictingPhpParserLibraryTest extends TestCase
{
    public function testItShouldNotConflictWhenIncludingPhpParserManually(): void
    {
        if (Version::id() >= 12) {
            $this->markTestSkipped('This test requires PHPUnit < 12');
        }

        include_once dirname(__DIR__, 1) . '/vendor/nikic/php-parser/lib/PhpParser/Parser.php';

        $this->assertTrue(true);
    }
}