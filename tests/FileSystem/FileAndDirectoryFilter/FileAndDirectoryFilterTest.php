<?php

declare(strict_types=1);

namespace Rector\Core\Tests\FileSystem\FileAndDirectoryFilter;

use PHPUnit\Framework\TestCase;
use Rector\Core\FileSystem\FileAndDirectoryFilter;

final class FileAndDirectoryFilterTest extends TestCase
{
    private FileAndDirectoryFilter $fileAndDirectoryFilter;

    protected function setUp(): void
    {
        $this->fileAndDirectoryFilter = new FileAndDirectoryFilter();
    }

    public function testSeparateFilesAndDirectories(): void
    {
        $sources = [__DIR__, __DIR__ . '/FileAndDirectoryFilterTest.php'];

        $files = $this->fileAndDirectoryFilter->filterFiles($sources);
        $directories = $this->fileAndDirectoryFilter->filterDirectories($sources);

        $this->assertCount(1, $files);
        $this->assertCount(1, $directories);

        $this->assertSame($files, [__DIR__ . '/FileAndDirectoryFilterTest.php']);
        $this->assertSame($directories, [__DIR__]);
    }
}
