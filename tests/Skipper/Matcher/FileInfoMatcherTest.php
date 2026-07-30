<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\Matcher;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[DataProvider('providePatterns')]
    public function testPatternNormalization(string $filePath, string $filePattern, bool $shouldMatch): void
    {
        $matchedPattern = $this->fileInfoMatcher->matchPattern($filePath, [$filePattern]);

        $this->assertSame($shouldMatch ? $filePattern : null, $matchedPattern);
    }

    /**
     * @return Iterator<array{string, string, bool}>
     */
    public static function providePatterns(): Iterator
    {
        // a pattern without asterisk is used as is, and matches the path suffix
        yield ['/project/path/with/no/asterisk', 'path/with/no/asterisk', true];
        yield ['/project/path/with/no/asterisk', 'path/with/another/asterisk', false];

        // an asterisk on either end is padded to both ends
        yield ['/project/path/with/asterisk/begin/Foo.php', '*path/with/asterisk/begin', true];
        yield ['/project/path/with/asterisk/end/Foo.php', 'path/with/asterisk/end*', true];

        // ".." in a pattern is resolved against the real path
        yield [__DIR__ . '/Fixture/path/in/it/KeepThisFile.txt', __DIR__ . '/Fixture/path/with/../in/it', true];
        yield [__DIR__ . '/Fixture/in/it/KeepThisFile.txt', __DIR__ . '/Fixture/path/with/../../in/it', true];
        yield [__DIR__ . '/Fixture/in/it/KeepThisFile.txt', __DIR__ . '/Fixture/path/with/../in/it', false];

        // a ".." pattern that resolves to nothing never matches
        yield [__DIR__ . '/Fixture/in/it/KeepThisFile.txt', __DIR__ . '/Fixture/missing/../nope', false];
    }
}
