<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\Matcher;

use Rector\Skipper\Matcher\FileInfoMatcher;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class FileInfoMatcherTest extends AbstractLazyTestCase
{
    private FileInfoMatcher $fileInfoMatcher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileInfoMatcher = $this->make(FileInfoMatcher::class);
    }

    public function testMatchPatternReturnsTheOriginalMatchedPattern(): void
    {
        $matchedPattern = $this->fileInfoMatcher->matchPattern('/project/src/Foo.php', ['*/tests/*', '*/src/*']);

        // the original, un-normalized pattern is returned, so callers can report the exact path
        $this->assertSame('*/src/*', $matchedPattern);
    }

    public function testMatchPatternReturnsNullWhenNoPatternMatches(): void
    {
        $matchedPattern = $this->fileInfoMatcher->matchPattern('/project/src/Foo.php', ['*/tests/*']);

        $this->assertNull($matchedPattern);
    }

    public function testDoesFileInfoMatchPatternsStillReportsBoolean(): void
    {
        $this->assertTrue($this->fileInfoMatcher->doesFileInfoMatchPatterns('/project/src/Foo.php', ['*/src/*']));
        $this->assertFalse($this->fileInfoMatcher->doesFileInfoMatchPatterns('/project/src/Foo.php', ['*/tests/*']));
    }
}
