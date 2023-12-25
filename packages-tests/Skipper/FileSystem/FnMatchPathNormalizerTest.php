<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\FileSystem;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Skipper\FileSystem\FnMatchPathNormalizer;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class FnMatchPathNormalizerTest extends AbstractLazyTestCase
{
    private FnMatchPathNormalizer $fnMatchPathNormalizer;

    protected function setUp(): void
    {
        $this->fnMatchPathNormalizer = $this->make(FnMatchPathNormalizer::class);
    }

    #[DataProvider('providePaths')]
    public function testPaths(string $path, string $expectedNormalizedPath): void
    {
        $normalizedPath = $this->fnMatchPathNormalizer->normalizeForFnmatch($path);
        $this->assertSame($expectedNormalizedPath, $normalizedPath);
    }

    public static function providePaths(): Iterator
    {
        yield ['path/with/no/asterisk', 'path/with/no/asterisk'];
        yield ['*path/with/asterisk/begin', '*path/with/asterisk/begin*'];
        yield ['path/with/asterisk/end*', '*path/with/asterisk/end*'];
        yield ['*path/with/asterisk/begin/and/end*', '*path/with/asterisk/begin/and/end*'];
        yield [
            __DIR__ . '/Fixture/path/with/../in/it',
            __DIR__ . DIRECTORY_SEPARATOR . 'Fixture' . DIRECTORY_SEPARATOR . 'path' . DIRECTORY_SEPARATOR . 'in' . DIRECTORY_SEPARATOR . 'it',
        ];
        yield [
            __DIR__ . '/Fixture/path/with/../../in/it',
            __DIR__ . DIRECTORY_SEPARATOR . 'Fixture' . DIRECTORY_SEPARATOR . 'in' . DIRECTORY_SEPARATOR . 'it',
        ];
    }
}
