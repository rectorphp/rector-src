<?php

declare(strict_types=1);

namespace Rector\Tests\Util;

use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Util\FileHasher;
use Symfony\Component\Filesystem\Filesystem;

final class FileHasherTest extends AbstractLazyTestCase
{
    private FileHasher $fileHasher;

    private Filesystem $filesystem;

    protected function setUp(): void
    {
        $this->fileHasher = $this->make(FileHasher::class);
        $this->filesystem = $this->make(Filesystem::class);
    }

    public function testHash(): void
    {
        $hash = $this->fileHasher->hash('some string');
        $this->assertSame('8df638f91bacc826bf50c04efd7df1b1', $hash);
    }

    public function testHashFiles(): void
    {
        $dir = sys_get_temp_dir();
        $file = $dir . '/FileHasherTest-fixture.txt';

        try {
            $this->filesystem->dumpFile($file, 'some string');

            $hash = $this->fileHasher->hashFiles([$file]);
            $this->assertSame('8df638f91bacc826bf50c04efd7df1b1', $hash);
        } finally {
            $this->filesystem->remove($file);
        }
    }

    public function testHashFilesWithEmptyArray(): void
    {
        $hash = $this->fileHasher->hashFiles([]);
        $this->assertSame('', $hash);
    }
}
