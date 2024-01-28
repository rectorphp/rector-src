<?php

declare(strict_types=1);

namespace Rector\Tests\Util;

use Nette\Utils\FileSystem;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Util\FileHasher;

final class FileHasherTest extends AbstractLazyTestCase
{
    private FileHasher $fileHasher;

    protected function setUp(): void
    {
        $this->fileHasher = $this->make(FileHasher::class);
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
            FileSystem::write($file, 'some string', null);

            $hash = $this->fileHasher->hashFiles([$file]);
            $this->assertSame('8df638f91bacc826bf50c04efd7df1b1', $hash);
        } finally {
            FileSystem::delete($file);
        }
    }

    public function testHashFilesWithEmptyArray(): void
    {
        $hash = $this->fileHasher->hashFiles([]);
        $this->assertSame('', $hash);
    }
}
