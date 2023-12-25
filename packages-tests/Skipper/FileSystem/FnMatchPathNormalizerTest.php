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
        yield [
            'path/with/no/asterisk',
            'path' . DIRECTORY_SEPARATOR . 'with' . DIRECTORY_SEPARATOR . 'no' . DIRECTORY_SEPARATOR . 'asterisk',
        ];
        yield [
            '*path/with/asterisk/begin',
            '*path' . DIRECTORY_SEPARATOR . 'with' . DIRECTORY_SEPARATOR . 'asterisk' . DIRECTORY_SEPARATOR . 'begin*',
        ];
        yield [
            'path/with/asterisk/end*',
            '*path' . DIRECTORY_SEPARATOR . 'with' . DIRECTORY_SEPARATOR . 'asterisk' . DIRECTORY_SEPARATOR . 'end*',
        ];
        yield [
            '*path/with/asterisk/begin/and/end*',
            '*path' . DIRECTORY_SEPARATOR . 'with' . DIRECTORY_SEPARATOR . 'asterisk' . DIRECTORY_SEPARATOR . 'begin' . DIRECTORY_SEPARATOR . 'and' . DIRECTORY_SEPARATOR . 'end*',
        ];
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
