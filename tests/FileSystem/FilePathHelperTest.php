<?php

declare(strict_types=1);

namespace Rector\Core\Tests\FileSystem;

use Iterator;
use PHPUnit\Framework\TestCase;
use Rector\Core\FileSystem\FilePathHelper;
use Symfony\Component\Filesystem\Filesystem;

final class FilePathHelperTest extends TestCase
{
    private FilePathHelper $filePathHelper;

    protected function setUp(): void
    {
        $this->filePathHelper = new FilePathHelper(new Filesystem());
    }

    /**
     * @dataProvider provideData()
     */
    public function test(string $inputPath, string $expectedNormalizedPath): void
    {
        $normalizedPath = $this->filePathHelper->normalizePathAndSchema($inputPath);
        $this->assertSame($expectedNormalizedPath, $normalizedPath);
    }

    public function provideData(): Iterator
    {
        // based on Linux
        yield ['/any/path', '/any/path'];
        yield ['\any\path', '/any/path'];
    }
}
