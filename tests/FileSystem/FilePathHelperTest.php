<?php

declare(strict_types=1);

namespace Rector\Tests\FileSystem;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\FileSystem\FilePathHelper;
use Symfony\Component\Filesystem\Filesystem;

final class FilePathHelperTest extends TestCase
{
    private FilePathHelper $filePathHelper;

    protected function setUp(): void
    {
        $this->filePathHelper = new FilePathHelper(new Filesystem());
    }

    #[DataProvider('provideData')]
    public function test(string $inputPath, string $expectedNormalizedPath): void
    {
        $normalizedPath = $this->filePathHelper->normalizePathAndSchema($inputPath);
        $this->assertSame($expectedNormalizedPath, $normalizedPath);
    }

    public static function provideData(): Iterator
    {
        // based on Linux
        yield ['/any/path', DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'path'];
        yield ['\any\path', DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'path'];
    }
}
